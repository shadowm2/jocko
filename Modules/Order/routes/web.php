<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Livewire\OrderEdit;
use Modules\Order\app\Livewire\OrdersList;

Route::middleware(['auth', 'verified'])
    ->prefix('orders')
    ->name('orders.')
    ->group(function () {
        Route::livewire('/list', OrdersList::class)->name('list');
        Route::livewire('/{order}/edit', OrderEdit::class)->name('edit');
    });
