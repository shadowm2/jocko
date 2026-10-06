<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Utils
{
    public static function invertColor(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = preg_replace('/(.)/', '$1$1', $hex);
        }

        $r = 255 - hexdec(substr($hex, 0, 2));
        $g = 255 - hexdec(substr($hex, 2, 2));
        $b = 255 - hexdec(substr($hex, 4, 2));

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    public static function darken(?string $hex = null, float $percent = 0.25): string
    {
        if ($hex === null) {
            return '';
        }
        $hex = ltrim($hex, '#');

        $r = max(0, (int) (hexdec(substr($hex, 0, 2)) * (1 - $percent)));
        $g = max(0, (int) (hexdec(substr($hex, 2, 2)) * (1 - $percent)));
        $b = max(0, (int) (hexdec(substr($hex, 4, 2)) * (1 - $percent)));

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    public static function iranianMobile(): string
    {
        $prefixes = [
            '0901', '0902', '0903', '0905',
            '0910', '0911', '0912', '0913', '0914',
            '0915', '0916', '0917', '0918', '0919',
            '0990', '0991', '0992', '0993',
            '0930', '0933', '0935', '0936', '0937',
            '0938', '0939',
            '0920', '0921', '0922',
        ];

        return fake()->randomElement($prefixes)
            .str_pad((string) fake()->numberBetween(0, 9999999), 7, '0', STR_PAD_LEFT);
    }

    public static function pDigits(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return strtr($value, [
            '0' => '۰',
            '1' => '۱',
            '2' => '۲',
            '3' => '۳',
            '4' => '۴',
            '5' => '۵',
            '6' => '۶',
            '7' => '۷',
            '8' => '۸',
            '9' => '۹',
        ]);
    }

    public static function eDigits(string $value): string
    {
        return strtr($value, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
        ]);
    }

    /**
     * @param  class-string<Model>  $model
     */
    public static function generateUniqueSlug(string $name, string $model, string $column = 'slug'): string
    {
        $baseSlug = self::generateBaseSlug($name);
        $slug = $baseSlug;
        $counter = 2;

        while ($model::where($column, $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public static function generateBaseSlug(string $name): string
    {
        $name = str_replace('-', '', $name);

        return Str::slug($name);
    }

    public static function getBaseSlug(string $slug): string
    {
        return preg_replace('/-\d+/', '', $slug);

    }

    public static function formatQuantity(float|int|string|null $value, int $decimals = 3, $thousandsSeparator = '/'): string
    {
        if (is_null($value)) {
            return '-';
        }

        /** @var string $formatted */
        $formatted = number_format((float) $value, $decimals, '.', $thousandsSeparator)
                |> (fn ($x) => rtrim($x, '0'))
                |> (fn ($x) => rtrim($x, '.'))
                |> self::pDigits(...);

        return $formatted;
    }
}
