<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('/dashboard', 'dashboard::dashboard')->name('dashboard');
    });

Route::get('/401', fn () => view('errors.401'));
Route::get('/403', fn () => view('errors.403'));
Route::get('/404', fn () => view('errors.404'));
Route::get('/419', fn () => view('errors.419'));
Route::get('/429', fn () => view('errors.429'));
Route::get('/500', fn () => view('errors.500'));
Route::get('/503', fn () => view('errors.503'));

require __DIR__.'/settings.php';
