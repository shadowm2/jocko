<?php

namespace Modules\Inventory\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Events\StockRunningLow;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\PurchaseItem;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\Warehouse;
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
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, StockMovement>|Collection<int, StockMovement>
     */
    public function getStockMovements(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
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
     * Bring the sale movements of a reference (an order) in line with its
     * current lines, taking the stock out of the warehouse. Pass no lines to
     * give everything back, e.g. for a cancelled order.
     *
     * Unit cost stays at its default: no cost basis is tracked yet and the
     * sale price already lives on the reference's own lines.
     *
     * @param  SupportCollection<int, Model>  $lines  anything with item_id, quantity, total and unit
     *
     * @throws ValidationException when a line asks for more than is on hand
     */
    public function syncSale(Model $reference, int $warehouseId, SupportCollection $lines): void
    {
        $lines = $lines->keyBy('item_id');

        $movements = StockMovement::query()
            ->where('reference_type', $reference->getMorphClass())
            ->where('reference_id', $reference->getKey())
            ->where('type', StockMovementType::SALE->value)
            ->get()
            ->keyBy('item_id');

        foreach ($movements->diffKeys($lines) as $movement) {
            WarehouseItem::find($movement->warehouse_item_id)?->increment('quantity', $movement->quantity);
            $movement->delete();
        }

        foreach ($lines as $itemId => $line) {
            // Locked so two sales cannot both spend the same stock.
            $warehouseItem = WarehouseItem::query()
                ->where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->lockForUpdate()
                ->first();

            if (! $warehouseItem) {
                continue;
            }

            // Put the old deduction back first, then take the full new quantity.
            $before = (float) $warehouseItem->quantity;
            $movement = $movements->get($itemId);
            if ($movement) {
                WarehouseItem::find($movement->warehouse_item_id)?->increment('quantity', $movement->quantity);
                $warehouseItem->refresh();
            }

            $quantity = $this->movementValues($line)['quantity'];

            if (round($quantity - (float) $warehouseItem->quantity, 3) > 0) {
                // Keyed like the order form's line inputs.
                throw ValidationException::withMessages([
                    "items.{$warehouseItem->slug}.quantity" => __('inventory::messages.Insufficient Stock'),
                ]);
            }

            $warehouseItem->decrement('quantity', $quantity);
            $this->warnIfLow($warehouseItem, $before);

            $attributes = [
                'warehouse_item_id' => $warehouseItem->id,
                'from_warehouse_id' => $warehouseId,
                'quantity' => $quantity,
                'balance_after' => (float) $warehouseItem->quantity,
            ];

            if ($movement) {
                $movement->update($attributes);

                continue;
            }

            $this->repository->create([
                'slug' => Utils::generateUniqueSlug('sm '.$reference->slug.' '.$itemId, StockMovement::class),
                'item_id' => $itemId,
                'user_id' => auth()->id(),
                'to_warehouse_id' => null,
                'type' => StockMovementType::SALE->value,
                'reference_type' => $reference->getMorphClass(),
                'reference_id' => $reference->getKey(),
                'description' => __('inventory::strings.Sale Stock Out'),
                ...$attributes,
            ]);
        }
    }

    /**
     * Change a warehouse item's stock by a signed amount, in the item's base
     * unit (positive comes in, negative goes out), and record why. A zero
     * change records nothing.
     *
     * @throws ValidationException when more would leave than is on hand
     */
    public function adjust(WarehouseItem $warehouseItem, float $delta, StockMovementType $type, ?string $description = null): ?StockMovement
    {
        $delta = round($delta, 3);
        if ($delta === 0.0) {
            return null;
        }

        return DB::transaction(function () use ($warehouseItem, $delta, $type, $description): StockMovement {
            // Locked so two adjustments or sales cannot both spend the same stock.
            $warehouseItem = WarehouseItem::query()->lockForUpdate()->findOrFail($warehouseItem->id);

            if (round((float) $warehouseItem->quantity + $delta, 3) < 0) {
                throw ValidationException::withMessages([
                    'quantity' => __('inventory::messages.Insufficient Stock'),
                ]);
            }

            $before = (float) $warehouseItem->quantity;
            $warehouseItem->increment('quantity', $delta);
            $warehouseItem->refresh();

            if ($delta < 0) {
                $this->warnIfLow($warehouseItem, $before);
            }

            return $this->repository->create([
                'slug' => Utils::generateUniqueSlug('sm '.$warehouseItem->slug.' '.$type->value, StockMovement::class),
                'item_id' => $warehouseItem->item_id,
                'warehouse_item_id' => $warehouseItem->id,
                'user_id' => auth()->id(),
                'from_warehouse_id' => $delta < 0 ? $warehouseItem->warehouse_id : null,
                'to_warehouse_id' => $delta > 0 ? $warehouseItem->warehouse_id : null,
                'quantity' => abs($delta),
                'balance_after' => (float) $warehouseItem->quantity,
                'type' => $type->value,
                'description' => $description,
            ]);
        });
    }

    /**
     * Move stock of an item to another warehouse, in the item's base unit, as
     * a single TRANSFER movement (its balance_after is the source's). The
     * destination row is created when the item was never stocked there.
     *
     * @throws ValidationException for the same warehouse or more than is on hand
     */
    public function transfer(WarehouseItem $source, Warehouse $destination, float $quantity, ?string $description = null): StockMovement
    {
        $quantity = round($quantity, 3);
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('A transfer needs a positive quantity.');
        }

        if ((int) $source->warehouse_id === (int) $destination->id) {
            throw ValidationException::withMessages([
                'warehouse' => __('inventory::messages.Same Warehouse'),
            ]);
        }

        // Retried on deadlock: two opposite transfers lock the two rows in opposite order.
        return DB::transaction(function () use ($source, $destination, $quantity, $description): StockMovement {
            $source = WarehouseItem::query()->lockForUpdate()->findOrFail($source->id);

            if (round((float) $source->quantity - $quantity, 3) < 0) {
                throw ValidationException::withMessages([
                    'quantity' => __('inventory::messages.Insufficient Stock'),
                ]);
            }

            $target = WarehouseItem::query()
                ->where('warehouse_id', $destination->id)
                ->where('item_id', $source->item_id)
                ->lockForUpdate()
                ->first()
                ?? WarehouseItem::query()->create([
                    'slug' => Utils::generateUniqueSlug('wi '.$destination->slug.' '.$source->item->slug, WarehouseItem::class),
                    'warehouse_id' => $destination->id,
                    'item_id' => $source->item_id,
                    'quantity' => 0,
                ]);

            $before = (float) $source->quantity;
            $source->decrement('quantity', $quantity);
            $target->increment('quantity', $quantity);
            $source->refresh();

            $this->warnIfLow($source, $before);

            return $this->repository->create([
                'slug' => Utils::generateUniqueSlug('sm '.$source->slug.' transfer', StockMovement::class),
                'item_id' => $source->item_id,
                'warehouse_item_id' => $source->id,
                'user_id' => auth()->id(),
                'from_warehouse_id' => $source->warehouse_id,
                'to_warehouse_id' => $destination->id,
                'quantity' => $quantity,
                'balance_after' => (float) $source->quantity,
                'type' => StockMovementType::TRANSFER->value,
                'description' => $description,
            ]);
        }, 3);
    }

    /**
     * After stock was taken out: once the change is saved (not if it rolls
     * back), tell the user when the item is now at or below its minimum.
     */
    private function warnIfLow(WarehouseItem $warehouseItem, float $before): void
    {
        if ($warehouseItem->isLowStock()) {
            DB::afterCommit(fn () => event(new StockRunningLow($warehouseItem, $before)));
        }
    }

    /**
     * @param  array{quantity:float,unit_cost:float,total_cost:float}|null  $values
     */
    protected function createMovement(
        Purchase $purchase,
        PurchaseItem $line,
        WarehouseItem $warehouseItem,
        ?array $values = null,
    ): StockMovement {
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
     * the unit group unit marked as base. Works on purchase and order lines.
     *
     * @return array{quantity:float,unit_cost:float,total_cost:float}
     */
    private function movementValues(Model $line): array
    {
        $line->loadMissing(['unit', 'item.unitGroup.units']);

        $baseUnit = $line->item->unitGroup?->units
            ->first(fn (Unit $unit): bool => (bool) $unit->is_base);

        if (! $baseUnit) {
            throw new \InvalidArgumentException("Item [{$line->item_id}] has no base unit.");
        }

        if ($line->unit_id !== null && ! $line->unit) {
            throw new \InvalidArgumentException("Line [{$line->id}] has an unavailable unit.");
        }

        $purchaseUnit = $line->unit ?? $baseUnit;
        if ((int) $purchaseUnit->unit_group_id !== (int) $baseUnit->unit_group_id) {
            throw new \InvalidArgumentException("Line [{$line->id}] uses a unit from another unit group.");
        }

        $baseFactor = (float) $baseUnit->conversion_factor;
        $purchaseFactor = (float) $purchaseUnit->conversion_factor;
        if ($baseFactor <= 0 || $purchaseFactor <= 0) {
            throw new \InvalidArgumentException("Line [{$line->id}] has an invalid unit conversion factor.");
        }

        $conversionFactor = $purchaseFactor / $baseFactor;
        $purchaseQuantity = (float) $line->quantity;
        $baseQuantity = round($purchaseQuantity * $conversionFactor, 3);
        if ($purchaseQuantity > 0 && $baseQuantity <= 0) {
            throw new \InvalidArgumentException("Line [{$line->id}] converts to a quantity below warehouse precision.");
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
    //    public function return(): StockMovement {}
}
