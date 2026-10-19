<?php

use App\Helpers\Utils;

it('keeps the zeros of a whole number when there are no decimals', function () {
    expect(Utils::formatQuantity(2000, 0, ','))->toBe('۲,۰۰۰')
        ->and(Utils::formatQuantity(2000, 0, ''))->toBe('۲۰۰۰')
        ->and(Utils::formatQuantity(0, 0))->toBe('۰');
});

it('trims the zeros of a fraction but not those of the whole part', function () {
    expect(Utils::formatQuantity(2000, 3, ','))->toBe('۲,۰۰۰')
        ->and(Utils::formatQuantity(1500.5, 3, ','))->toBe('۱,۵۰۰.۵')
        ->and(Utils::formatQuantity(0, 3))->toBe('۰')
        ->and(Utils::formatQuantity('12.340', 3, ''))->toBe('۱۲.۳۴');
});

it('shows a dash for no value', function () {
    expect(Utils::formatQuantity(null))->toBe('-');
});
