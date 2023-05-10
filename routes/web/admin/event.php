<?php

use App\Http\Controllers\Administration\Event\BookingController;
use App\Http\Controllers\Administration\Event\CPTGeneratorController;
use Illuminate\Support\Facades\Route;

Route::prefix('event')->group(function () {
    Route::prefix('cpt')->group(function () {
        Route::get('/', [CPTGeneratorController::class, 'index'])->name('administration.event.cpt');
    });

    Route::prefix('booking')->group(function () {
        Route::post('{eventId}', [BookingController::class, 'update'])->name('administration.event.booking.update');
        Route::get('{eventId}', [BookingController::class, 'show'])->name('administration.event.booking.show');
        Route::get('', [BookingController::class, 'index'])->name('administration.event.booking');
    });
});
