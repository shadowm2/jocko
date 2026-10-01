<?php

use Illuminate\Support\Facades\Route;
use Modules\Car\Livewire\CarCreate;
use Modules\Car\Livewire\CarEdit;
use Modules\Car\Livewire\CarList;
use Modules\Car\Livewire\ColorCreate;
use Modules\Car\Livewire\ColorEdit;
use Modules\Car\Livewire\ColorList;
use Modules\Car\Livewire\CompanyCreate;
use Modules\Car\Livewire\CompanyEdit;
use Modules\Car\Livewire\CompanyList;

Route::middleware(['auth', 'verified'])
    ->prefix('cars')
    ->name('cars.')
    ->group(function () {
        Route::livewire('/list', CarList::class)->name('index');
        Route::livewire('/new', CarCreate::class)->name('create');
        Route::livewire('/{car}/edit', CarEdit::class)->name('edit');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('colors')
    ->name('colors.')
    ->group(function () {
        Route::livewire('/list', ColorList::class)->name('index');
        Route::livewire('/new', ColorCreate::class)->name('create');
        Route::livewire('/{color}/edit', ColorEdit::class)->name('edit');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('companies')
    ->name('companies.')
    ->group(function () {
        Route::livewire('/list', CompanyList::class)->name('index');
        Route::livewire('/new', CompanyCreate::class)->name('create');
        Route::livewire('/{company}/edit', CompanyEdit::class)->name('edit');
    });
