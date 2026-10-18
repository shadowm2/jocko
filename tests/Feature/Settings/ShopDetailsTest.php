<?php

use Livewire\Livewire;
use Modules\Dashboard\Livewire\SystemSettings;
use Modules\Dashboard\Models\Setting;
use Modules\Dashboard\Services\SettingService;
use Modules\User\Models\User;

it('saves the shop details and uses them as config on the next request', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(SystemSettings::class)
        ->set('values.shop.name', 'گاراژ جوکو')
        ->set('values.shop.address', 'تهران، ولیعصر')
        ->set('values.shop.phone', '02112345678')
        ->call('save')
        ->assertHasNoErrors();

    expect(Setting::where('key', 'like', 'shop.%')->count())->toBe(3);

    // What the service provider does at the start of every request.
    resolve(SettingService::class)->applyToConfig();

    expect(config('app.name'))->toBe('گاراژ جوکو')
        ->and(config('shop.address'))->toBe('تهران، ولیعصر')
        ->and(config('shop.phone'))->toBe('02112345678');
});

it('keeps the .env app name when the shop name is left blank', function () {
    $this->actingAs(User::factory()->create());
    $envName = config('app.name');

    Livewire::test(SystemSettings::class)->call('save')->assertHasNoErrors();
    resolve(SettingService::class)->applyToConfig();

    expect(config('app.name'))->toBe($envName)
        ->and(Setting::where('key', 'like', 'shop.%')->count())->toBe(0);
});
