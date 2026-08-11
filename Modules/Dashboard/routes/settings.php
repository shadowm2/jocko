<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\app\Livewire\Security;
use Modules\User\Livewire\Settings\Appearance;
use Modules\User\Livewire\Settings\Profile;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/security', Security::class)
        ->middleware([
            'password.confirm',
        ])
        ->name('security.edit');
});
