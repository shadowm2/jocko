<?php

namespace Modules\Inventory\Listeners;

use App\Helpers\Utils;
use Flux\Flux;
use Modules\Inventory\Events\StockRunningLow;

class NotifyLowStock
{
    public function handle(StockRunningLow $event): void
    {
        // Only a Livewire request has a page to show the toast on, not seeders or jobs.
        if (! app('livewire')->current()) {
            return;
        }

        $warehouseItem = $event->warehouseItem->loadMissing(['item', 'warehouse']);

        Flux::toast(
            text: __('inventory::messages.Low Stock Warning', [
                'item' => $warehouseItem->item->name,
                'warehouse' => $warehouseItem->warehouse->name,
                'quantity' => Utils::pDigits(Utils::formatQuantity($warehouseItem->quantity, 3, ',')),
                'minimum' => Utils::pDigits(Utils::formatQuantity($warehouseItem->min_quantity, 3, ',')),
            ]),
            variant: 'warning',
        );
    }
}
