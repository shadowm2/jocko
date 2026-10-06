<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseItem;
use Modules\Inventory\Models\StockMovement;
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
        foreach ($purchase->items()->get() as $line) {
            /** @var ?WarehouseItem $warehouseItem */
            $warehouseItem = $purchase->warehouse->warehouseItems()
                ->where('item_id', $line->item_id)
                ->first();

            if (! $warehouseItem) {
                continue;
            }

            $warehouseItem->increment('quantity', $line->quantity);
            $warehouseItem->refresh();

            $movements->push($this->createMovement($purchase, $line, $warehouseItem));
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
        $lines = $purchase->items()->get()->keyBy('item_id');

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

            $movement = $movements->get($line->item_id);

            if (! $movement) {
                $warehouseItem->increment('quantity', $line->quantity);
                $warehouseItem->refresh();

                $kept->push($this->createMovement($purchase, $line, $warehouseItem));

                continue;
            }

            // The warehouse item may have changed since the movement was
            // recorded; revert the old one before applying the new delta.
            if ((int) $movement->warehouse_item_id !== (int) $warehouseItem->id) {
                WarehouseItem::find($movement->warehouse_item_id)
                    ?->decrement('quantity', $movement->quantity);
                $warehouseItem->increment('quantity', $line->quantity);
            } else {
                $warehouseItem->increment('quantity', $line->quantity - $movement->quantity);
            }

            $warehouseItem->refresh();
            $unitCost = (float) $line->unit_price;

            $movement->update([
                'warehouse_item_id' => $warehouseItem->id,
                'to_warehouse_id' => $purchase->warehouse_id,
                'quantity' => $line->quantity,
                'unit_cost' => $unitCost,
                'total_cost' => round($line->quantity * $unitCost, 4),
                'balance_after' => (float) $warehouseItem->quantity,
            ]);

            $kept->push($movement);
        }

        return $kept;
    }

    protected function createMovement(Purchase $purchase, PurchaseItem $line, WarehouseItem $warehouseItem): StockMovement
    {
        $unitCost = (float) $line->unit_price;

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
            'quantity' => $line->quantity,
            'unit_cost' => $unitCost,
            'total_cost' => round($line->quantity * $unitCost, 4),
            'balance_after' => (float) $warehouseItem->quantity,
            'type' => StockMovementType::PURCHASE->value,
            'reference_type' => $purchase->getMorphClass(),
            'reference_id' => $purchase->id,
            'description' => __('inventory::strings.Purchase Stock In'),
        ]);
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
