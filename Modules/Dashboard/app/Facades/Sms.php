<?php

namespace Modules\Dashboard\Facades;

use Illuminate\Support\Facades\Facade;
use Kavenegar\Models\SendResult;
use Modules\Dashboard\Services\SmsService;

/**
 * @method static list<SendResult> send(string|list<string> $to, string $message, ?string $sender = null)
 * @method static list<SendResult> lookup(string $to, string $template, string|list<string> $tokens)
 *
 * @see SmsService
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmsService::class;
    }
}
