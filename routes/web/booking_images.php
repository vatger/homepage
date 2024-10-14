<?php

use App\Http\Controllers\BookingImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('booking/image')
    ->middleware('cookie.redirect')
    ->group(function () {
        Route::get("/dark", [BookingImageController::class, 'setDarkMode']);
        Route::get("/light", [BookingImageController::class, 'setLightMode']);
        Route::get('{image_id}', [BookingImageController::class, 'serveBookingImage']);
    });
