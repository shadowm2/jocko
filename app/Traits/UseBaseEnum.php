<?php

namespace App\Traits;

trait UseBaseEnum
{
    /** @return array<int, string|int> */
    public static function values(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
