<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Livewire\UserAdd;
use Modules\User\Livewire\UserCar\UserCarEdit;
use Modules\User\Livewire\UserCarsList;
use Modules\User\Livewire\UserEdit;
use Modules\User\Livewire\UsersList;

Route::middleware(['auth', 'verified'])
    ->prefix('/users')
    ->name('users.')
    ->group(function () {
        Route::name('')
            ->group(function () {
                Route::livewire('/list', UsersList::class)->name('list');
                Route::livewire('/new', UserAdd::class)->name('add');
                Route::livewire('/{user}/edit', UserEdit::class)->name('edit');
            });

        Route::prefix('/{user}/cars')
            ->name('cars.')
            ->group(function () {
                Route::livewire('/list', UserCarsList::class)->name('list');
                Route::livewire('/{userCar}/edit', UserCarEdit::class)->name('edit');
            });

    });
