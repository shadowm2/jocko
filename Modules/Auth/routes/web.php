<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Livewire\OtpLogin;

Route::livewire('/login/otp', OtpLogin::class)->middleware('guest')->name('login.otp');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('auths', AuthController::class)->names('auths');
});

Route::get('/force-429', function () {
    throw new Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException(
        60, // Retry after seconds
        'Too Many Requests - Test mode' // Custom message
    );

});
