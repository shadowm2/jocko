<?php

namespace Modules\Inventory\Events;

use Modules\Inventory\Models\WarehouseItem;

/**
 * Stock was taken out and the item is now at or below its minimum.
 */
class StockRunningLow
{
    public function __construct(public readonly WarehouseItem $warehouseItem) {}
}
