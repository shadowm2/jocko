<?php

namespace Modules\Inventory\Listeners;

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

        Flux::toast(text: $event->message(), variant: 'warning');
    }
}
