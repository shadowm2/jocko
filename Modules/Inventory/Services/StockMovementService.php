<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseItem;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\WarehouseItem;
use Modules\Inventory\Repositories\StockMovementRepository;

/**
 * @extends BaseService<StockMovement, StockMovementRepository>
 */
class StockMovementService extends BaseService
{
    public function __construct(
        StockMovementRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * Record the purchase as stock coming into its warehouse: one
     * inbound movement per line, added to the warehouse item's quantity.
     *
     * @return Collection<int, StockMovement>
     */
    public function purchase(Purchase $purchase): Collection
    {
        $movements = new Collection;

        /** @var PurchaseItem $line */
        foreach ($purchase->items()->with(['unit', 'item.unitGroup.units'])->get() as $line) {
            /** @var ?WarehouseItem $warehouseItem */
            $warehouseItem = $purchase->warehouse->warehouseItems()
                ->where('item_id', $line->item_id)
                ->first();

            if (! $warehouseItem) {
                continue;
            }

            $values = $this->movementValues($line);
            $warehouseItem->increment('quantity', $values['quantity']);
            $warehouseItem->refresh();

            $movements->push($this->createMovement($purchase, $line, $warehouseItem, $values));
        }

        return $movements;
    }

    /**
     * Bring the purchase's existing movements in line with its current
     * lines instead of dropping and recreating them:
     *
     * - kept lines have their movement updated in place and the
     *   warehouse item adjusted by the quantity delta,
     * - new lines get a fresh movement,
     * - removed lines have their effect reverted and their movement
     *   soft deleted.
     */
    public function syncPurchase(Purchase $purchase): Collection
    {
        $lines = $purchase->items()
            ->with(['unit', 'item.unitGroup.units'])
            ->get()
            ->keyBy('item_id');

        $movements = StockMovement::query()
            ->where('reference_type', $purchase->getMorphClass())
            ->where('reference_id', $purchase->id)
            ->where('type', StockMovementType::PURCHASE->value)
            ->get()
            ->keyBy('item_id');

        foreach ($movements as $itemId => $movement) {
            if ($lines->has($itemId)) {
                continue;
            }

            WarehouseItem::find($movement->warehouse_item_id)
                ?->decrement('quantity', $movement->quantity);

            $movement->delete();
        }

        $kept = new Collection;

        foreach ($lines as $line) {
            $warehouseItem = $purchase->warehouse->warehouseItems()
                ->where('item_id', $line->item_id)
                ->first();

            if (! $warehouseItem) {
                continue;
            }

            $values = $this->movementValues($line);
            $movement = $movements->get($line->item_id);

            if (! $movement) {
                $warehouseItem->increment('quantity', $values['quantity']);
                $warehouseItem->refresh();

                $kept->push($this->createMovement($purchase, $line, $warehouseItem, $values));

                continue;
            }

            // The warehouse item may have changed since the movement was
            // recorded; revert the old one before applying the new delta.
            if ((int) $movement->warehouse_item_id !== (int) $warehouseItem->id) {
                WarehouseItem::find($movement->warehouse_item_id)
                    ?->decrement('quantity', $movement->quantity);
                $warehouseItem->increment('quantity', $values['quantity']);
            } else {
                $warehouseItem->increment('quantity', $values['quantity'] - (float) $movement->quantity);
            }

            $warehouseItem->refresh();

            $movement->update([
                'warehouse_item_id' => $warehouseItem->id,
                'to_warehouse_id' => $purchase->warehouse_id,
                'quantity' => $values['quantity'],
                'unit_cost' => $values['unit_cost'],
                'total_cost' => $values['total_cost'],
                'balance_after' => (float) $warehouseItem->quantity,
            ]);

            $kept->push($movement);
        }

        return $kept;
    }

    /**
     * @param  array{quantity:float,unit_cost:float,total_cost:float}|null  $values
     */
    protected function createMovement(
        Purchase $purchase,
        PurchaseItem $line,
        WarehouseItem $warehouseItem,
        ?array $values = null,
    ): StockMovement
    {
        $values ??= $this->movementValues($line);

        return $this->repository->create([
            'slug' => Utils::generateUniqueSlug(
                'sm '.$purchase->slug.' '.$line->item_id,
                StockMovement::class
            ),
            'item_id' => $line->item_id,
            'warehouse_item_id' => $warehouseItem->id,
            'user_id' => auth()->id(),
            'from_warehouse_id' => null,
            'to_warehouse_id' => $purchase->warehouse_id,
            'quantity' => $values['quantity'],
            'unit_cost' => $values['unit_cost'],
            'total_cost' => $values['total_cost'],
            'balance_after' => (float) $warehouseItem->quantity,
            'type' => StockMovementType::PURCHASE->value,
            'reference_type' => $purchase->getMorphClass(),
            'reference_id' => $purchase->id,
            'description' => __('inventory::strings.Purchase Stock In'),
        ]);
    }

    /**
     * Convert the purchase quantity and price to the item's base unit before
     * applying stock changes. Warehouse item quantities are always stored in
     * the unit group unit marked as base.
     *
     * @return array{quantity:float,unit_cost:float,total_cost:float}
     */
    private function movementValues(PurchaseItem $line): array
    {
        $line->loadMissing(['unit', 'item.unitGroup.units']);

        $baseUnit = $line->item->unitGroup?->units
            ->first(fn (Unit $unit): bool => (bool) $unit->is_base);

        if (! $baseUnit) {
            throw new \InvalidArgumentException("Item [{$line->item_id}] has no base unit.");
        }

        if ($line->unit_id !== null && ! $line->unit) {
            throw new \InvalidArgumentException("Purchase item [{$line->id}] has an unavailable unit.");
        }

        $purchaseUnit = $line->unit ?? $baseUnit;
        if ((int) $purchaseUnit->unit_group_id !== (int) $baseUnit->unit_group_id) {
            throw new \InvalidArgumentException("Purchase item [{$line->id}] uses a unit from another unit group.");
        }

        $baseFactor = (float) $baseUnit->conversion_factor;
        $purchaseFactor = (float) $purchaseUnit->conversion_factor;
        if ($baseFactor <= 0 || $purchaseFactor <= 0) {
            throw new \InvalidArgumentException("Purchase item [{$line->id}] has an invalid unit conversion factor.");
        }

        $conversionFactor = $purchaseFactor / $baseFactor;
        $purchaseQuantity = (float) $line->quantity;
        $baseQuantity = round($purchaseQuantity * $conversionFactor, 3);
        if ($purchaseQuantity > 0 && $baseQuantity <= 0) {
            throw new \InvalidArgumentException(__('inventory::messages.Purchase item [:id] converts to a quantity below warehouse precision.', ['id' => $line->id]));
        }

        $totalCost = round((float) $line->total, 4);

        return [
            'quantity' => $baseQuantity,
            'unit_cost' => $baseQuantity > 0 ? round($totalCost / $baseQuantity, 4) : 0,
            'total_cost' => $totalCost,
        ];
    }
    //
    //    public function consume(): StockMovement
    //    {
    //        // decrease warehouse stock
    //        // create movement
    //    }
    //
    //    public function transfer(): StockMovement
    //    {
    //        // decrease source
    //        // increase destination
    //        // create movement
    //    }
    //
    //    public function adjust(): StockMovement
    //    {
    //        // change stock
    //        // create movement
    //    }
    //
    //    public function return(): StockMovement {}
}
