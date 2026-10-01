<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Livewire\UserCreate;
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
                Route::livewire('/list', UsersList::class)->name('index');
                Route::livewire('/new', UserCreate::class)->name('create');
                Route::livewire('/{user}/edit', UserEdit::class)->name('edit');
            });

        Route::prefix('/{user}/cars')
            ->name('cars.')
            ->group(function () {
                Route::livewire('/list', UserCarsList::class)->name('index');
                Route::livewire('/{userCar}/edit', UserCarEdit::class)->name('edit');
            });

    });
