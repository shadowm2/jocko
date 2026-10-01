<?php

namespace App\Traits;

trait UseBaseEnum
{
    public static function values(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
