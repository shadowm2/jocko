<?php

namespace App\Rules;

use App\Helpers\Utils;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Morilog\Jalali\Jalalian;

class JalaliDate implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (! is_string($value) || empty($value)) {
            $fail(__('validation.jalali_date'));

            return;
        }

        try {
            $value = str_replace('-', '/', $value);
            $value = Utils::eDigits($value);

            $d = Jalalian::fromFormat('Y/m/d', $value);
            if ($d->toCarbon()->isAfter(now()->addYears(50)) || $d->toCarbon()->isBefore(now()->subYears(100))) {
                $fail(__('validation.jalali_date'));
            }
        } catch (\Throwable $e) {
            $fail(__('validation.jalali_date'));
        }
    }
}
