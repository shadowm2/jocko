<?php

namespace Modules\Inventory\Events;

use App\Helpers\Utils;
use Modules\Inventory\Models\WarehouseItem;

/**
 * Stock was taken out and the item is now at or below its minimum.
 */
class StockRunningLow
{
    public function __construct(
        public readonly WarehouseItem $warehouseItem,
        public readonly float $before,
    ) {}

    /**
     * True when this change is what took the item down to its minimum, as
     * opposed to one more withdrawal from an item that was already low.
     */
    public function crossedMinimum(): bool
    {
        return $this->before > (float) $this->warehouseItem->min_quantity;
    }

    public function message(): string
    {
        $warehouseItem = $this->warehouseItem->loadMissing(['item', 'warehouse']);

        return __('inventory::messages.Low Stock Warning', [
            'item' => $warehouseItem->item->name,
            'warehouse' => $warehouseItem->warehouse->name,
            'quantity' => Utils::formatQuantity($warehouseItem->quantity, 3, ','),
            'minimum' => Utils::formatQuantity($warehouseItem->min_quantity, 3, ','),
        ]);
    }
}
