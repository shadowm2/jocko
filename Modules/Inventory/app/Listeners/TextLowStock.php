<?php

namespace Modules\Inventory\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Dashboard\Exceptions\SmsException;
use Modules\Dashboard\Facades\Sms;
use Modules\Dashboard\Services\SettingService;
use Modules\Inventory\Events\StockRunningLow;

/**
 * Texts the shop's alert number once, when an item first drops to its minimum.
 *
 * Queued, so saving an order never waits for Kavenegar: a queue worker has to
 * be running (php artisan queue:work) for the text to go out.
 */
class TextLowStock implements ShouldQueue
{
    public function shouldQueue(StockRunningLow $event): bool
    {
        return filled(config('shop.alert_mobile')) && $event->crossedMinimum();
    }

    public function handle(StockRunningLow $event): void
    {
        // A worker boots once; pick up the number and API key saved on System Settings since.
        resolve(SettingService::class)->applyToConfig();

        try {
            Sms::send(config('shop.alert_mobile'), $event->message());
        } catch (SmsException) {
            // Already logged by SmsService; a failed text is not worth failing the job for.
        }
    }
}
