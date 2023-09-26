<?php

use App\OpenApi\Controllers\AtcApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('booking')->group(function () {
        Route::prefix('atc')->group(function () {
            Route::get('filter', [AtcApiController::class, 'show'])->name('api.booking.atc.filter');
            Route::get('personal', [AtcApiController::class, 'personal'])->name('api.booking.atc.personal');
            Route::get('daterange/{start}/{end?}', [AtcApiController::class, 'index'])
                ->withoutMiddleware('auth:sanctum')
                ->name('api.booking.atc');
        });
    });
});
