<?php

use Illuminate\Support\Facades\Route;

// Guests go on to the login page through the dashboard's auth middleware.
Route::redirect('/', '/dashboard')->name('home');
