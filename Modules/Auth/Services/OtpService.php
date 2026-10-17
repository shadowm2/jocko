<?php

namespace Modules\Auth\Services;

use App\Helpers\Utils;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Modules\Dashboard\Exceptions\SmsException;
use Modules\Dashboard\Facades\Sms;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;

/**
 * SMS one-time codes for admin login. A code is kept only as an HMAC, expires
 * after TTL_MINUTES, works once, and is burned after MAX_FAILURES wrong guesses.
 */
class OtpService
{
    public const LENGTH = 6;

    private const TTL_MINUTES = 5;

    private const MAX_FAILURES = 5;

    /**
     * Text a login code to the admin with this mobile.
     *
     * @throws ValidationException when requested too often or no admin has this mobile
     * @throws SmsException when the SMS could not be sent
     */
    public function send(string $mobile): void
    {
        $mobile = Utils::normalizeMobile($mobile);

        // Counted before the lookup so guessing numbers is rate limited too.
        $this->throttle("otp-send:{$mobile}", 1, 60);
        $this->throttle('otp-send-ip:'.request()->ip(), 10, 3600);

        $user = $this->admin($mobile);

        if (! $user) {
            throw ValidationException::withMessages(['mobile' => __('auth::strings.This mobile number is not registered for login.')]);
        }

        $code = str_pad((string) random_int(0, 10 ** self::LENGTH - 1), self::LENGTH, '0', STR_PAD_LEFT);

        $user->forceFill(['otp_code' => $this->digest($user, $code), 'otp_issued_at' => now()])->saveQuietly();
        RateLimiter::clear("otp-failures:{$user->id}");

        try {
            $template = config('services.kavenegar.otp_template');

            $template
                ? Sms::lookup($user->mobile, $template, $code)
                : Sms::send($user->mobile, __('auth::strings.Your login code is :code', ['code' => $code]));
        } catch (SmsException $e) {
            $this->burn($user);

            throw $e;
        }
    }

    /**
     * The admin this code was issued to, or null when the mobile or the code
     * is wrong or expired. A correct code is used up.
     */
    public function verify(string $mobile, string $code): ?User
    {
        $user = $this->admin(Utils::normalizeMobile($mobile));

        if (! $user || blank($user->otp_code) || ! $user->otp_issued_at?->isAfter(now()->subMinutes(self::TTL_MINUTES))) {
            return null;
        }

        if (! hash_equals($user->otp_code, $this->digest($user, Utils::eDigits(trim($code))))) {
            $key = "otp-failures:{$user->id}";
            RateLimiter::hit($key, self::TTL_MINUTES * 60);

            if (RateLimiter::attempts($key) >= self::MAX_FAILURES) {
                $this->burn($user);
            }

            return null;
        }

        $this->burn($user);

        return $user;
    }

    private function admin(string $mobile): ?User
    {
        return User::where('type', UserType::Admin)->where('mobile', $mobile)->first();
    }

    private function digest(User $user, string $code): string
    {
        return hash_hmac('sha256', $user->id.'|'.$code, config('app.key'));
    }

    private function burn(User $user): void
    {
        $user->forceFill(['otp_code' => null, 'otp_issued_at' => null])->saveQuietly();
        RateLimiter::clear("otp-failures:{$user->id}");
    }

    private function throttle(string $key, int $max, int $decaySeconds): void
    {
        if (RateLimiter::tooManyAttempts($key, $max)) {
            throw ValidationException::withMessages([
                'mobile' => trans('auth::auth.throttle', ['seconds' => RateLimiter::availableIn($key)]),
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }
}
