<?php

use Illuminate\Support\Facades\Route;
use Modules\Car\Http\Controllers\CarController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cars', CarController::class)->names('car');
});
