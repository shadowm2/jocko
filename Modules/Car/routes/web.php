<?php

use Illuminate\Support\Facades\Route;
use Modules\Car\Livewire\CarAdd;
use Modules\Car\Livewire\CarEdit;
use Modules\Car\Livewire\CarList;
use Modules\Car\Livewire\ColorAdd;
use Modules\Car\Livewire\ColorEdit;
use Modules\Car\Livewire\ColorList;
use Modules\Car\Livewire\CompanyAdd;
use Modules\Car\Livewire\CompanyEdit;
use Modules\Car\Livewire\CompanyList;

Route::middleware(['auth', 'verified'])
    ->prefix('cars')
    ->name('cars.')
    ->group(function () {
        Route::livewire('/list', CarList::class)->name('index');
        Route::livewire('/new', CarAdd::class)->name('add');
        Route::livewire('/{car}/edit', CarEdit::class)->name('edit');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('colors')
    ->name('colors.')
    ->group(function () {
        Route::livewire('/list', ColorList::class)->name('index');
        Route::livewire('/new', ColorAdd::class)->name('add');
        Route::livewire('/{color}/edit', ColorEdit::class)->name('edit');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('companies')
    ->name('companies.')
    ->group(function () {
        Route::livewire('/list', CompanyList::class)->name('index');
        Route::livewire('/new', CompanyAdd::class)->name('add');
        Route::livewire('/{company}/edit', CompanyEdit::class)->name('edit');
    });
