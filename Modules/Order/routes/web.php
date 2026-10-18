<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Livewire\OrderCreate;
use Modules\Order\Livewire\OrderEdit;
use Modules\Order\Livewire\OrdersList;
use Modules\Order\Livewire\PaymentCreate;
use Modules\Order\Livewire\PaymentEdit;
use Modules\Order\Livewire\PaymentsList;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::livewire('/list', OrdersList::class)->name('index');
        Route::livewire('/new', OrderCreate::class)->name('create');
        Route::livewire('/{order}/edit', OrderEdit::class)->name('edit');
    });

    Route::prefix('payments')->as('payments.')->group(function () {
        Route::livewire('/list', PaymentsList::class)->name('index');
        Route::livewire('/new', PaymentCreate::class)->name('create');
        Route::livewire('/{payment}/edit', PaymentEdit::class)->name('edit');
    });
});
