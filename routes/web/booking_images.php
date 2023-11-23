<?php

use App\Http\Controllers\BookingImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('booking/image')
    ->group(function() {
        Route::get('{image_id}', [BookingImageController::class, 'serveBookingImage']);
    });
