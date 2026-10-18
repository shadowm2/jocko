<?php

namespace Modules\Dashboard\Services;

use App\Helpers\Utils;
use Closure;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Kavenegar\Exceptions\BaseRuntimeException;
use Kavenegar\KavenegarApi;
use Kavenegar\Models\SendResult;
use Modules\Dashboard\Exceptions\SmsException;

/**
 * The one place that talks to the Kavenegar SDK. Use it through the Sms facade:
 *
 *     Sms::send('09121234567', 'Your order is ready');
 *     Sms::lookup('09121234567', 'order-ready', ['ORD-00012']);
 *
 * Credentials are read from config('services.kavenegar.*') on every call, so
 * a change on the System Settings page applies immediately. With SMS_ENABLED=false
 * in .env (outside production) nothing is sent: the SMS is written to the log.
 * Both methods throw
 * SmsException on failure; catch it where a failed SMS must not break the flow.
 *
 * ponytail: sends are synchronous (the SDK waits up to 30s). Dispatch a queued
 * job that calls this where a request must not wait for Kavenegar.
 */
#[Singleton]
class SmsService
{
    /**
     * Free-text SMS. Without $sender the configured sender line is used.
     *
     * @param  string|list<string>  $to
     * @return list<SendResult>
     */
    public function send(string|array $to, string $message, ?string $sender = null): array
    {
        $to = array_map(static fn (string $number) => Utils::eDigits(trim($number)), (array) $to);
        $sender ??= config('services.kavenegar.sender') ?: null;

        return $this->call('send', $to, ['message' => $message], static fn (KavenegarApi $api) => $api->send($sender, $to, $message));
    }

    /**
     * Template SMS (Kavenegar's verify/lookup): the template is created in the
     * Kavenegar panel and the tokens fill its %token, %token2 and %token3.
     * Tokens may not contain spaces. This is the right way to send OTP codes.
     *
     * @param  string|list<string>  $tokens
     * @return list<SendResult>
     */
    public function lookup(string $to, string $template, string|array $tokens): array
    {
        $tokens = array_values((array) $tokens);

        if (count($tokens) > 3) {
            throw new InvalidArgumentException('A Kavenegar lookup template takes at most 3 tokens.');
        }

        $to = Utils::eDigits(trim($to));

        return $this->call('lookup', [$to], ['template' => $template, 'tokens' => $tokens], static fn (KavenegarApi $api) => $api->verifyLookup($to, $tokens[0] ?? null, $tokens[1] ?? null, $tokens[2] ?? null, $template));
    }

    /**
     * @param  list<string>  $to  only used for the log
     * @param  array<string, mixed>  $content  only used for the log when SMS is disabled
     * @param  Closure(KavenegarApi): mixed  $request
     * @return list<SendResult>
     */
    private function call(string $action, array $to, array $content, Closure $request): array
    {
        // SMS_ENABLED=false: log instead of send, for local testing. Never in
        // production, where an OTP code in the log would be a way to log in.
        if (! config('services.kavenegar.enabled') && ! app()->isProduction()) {
            Log::info("SMS not sent, SMS_ENABLED is false ({$action})", ['to' => $to] + $content);

            return [];
        }

        $key = config('services.kavenegar.key');

        if (blank($key)) {
            // The SDK die()s on a missing key, so never get that far.
            Log::warning("SMS not sent ({$action}): the Kavenegar API key is not set", ['to' => $to]);

            throw new SmsException('The Kavenegar API key is not set. Add it in System Settings.');
        }

        try {
            // Through the container so tests can swap in a fake. The SDK
            // defaults to not verifying TLS, which is wrong for an API key.
            return Arr::wrap($request(app()->makeWith(KavenegarApi::class, ['apiKey' => $key, 'sslVerify' => true])));
        } catch (BaseRuntimeException $e) {
            Log::warning("SMS failed ({$action}): {$e->getMessage()}", ['to' => $to, 'code' => $e->getCode()]);

            throw new SmsException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
