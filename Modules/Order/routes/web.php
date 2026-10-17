<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Livewire\OrderCreate;
use Modules\Order\Livewire\OrderEdit;
use Modules\Order\Livewire\OrdersList;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::livewire('/list', OrdersList::class)->name('index');
        Route::livewire('/new', OrderCreate::class)->name('create');
        Route::livewire('/{order}/edit', OrderEdit::class)->name('edit');
    });
});
