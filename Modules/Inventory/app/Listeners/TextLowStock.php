<?php

namespace Modules\Inventory\Listeners;

use Modules\Dashboard\Exceptions\SmsException;
use Modules\Dashboard\Facades\Sms;
use Modules\Inventory\Events\StockRunningLow;

/**
 * Texts the shop's alert number once, when an item first drops to its minimum.
 *
 * ponytail: sends inside the request that caused it (the SDK waits up to 30s).
 * Queue the listener if that ever slows saving an order.
 */
class TextLowStock
{
    public function handle(StockRunningLow $event): void
    {
        $to = config('shop.alert_mobile');

        if (blank($to) || ! $event->crossedMinimum()) {
            return;
        }

        try {
            Sms::send($to, $event->message());
        } catch (SmsException) {
            // Already logged by SmsService; a failed text must not fail the sale.
        }
    }
}
