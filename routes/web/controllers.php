<?php

use App\Http\Controllers\Controller\FeedbackController;
use App\Http\Controllers\User\Booking\AtcController;
use Illuminate\Support\Facades\Route;

Route::prefix('controllers')
    ->middleware(['auth', 'banned'])
    ->group(function () {
        Route::prefix('booking')->group(function () {
            Route::get('{booking}/edit', [AtcController::class, 'edit'])->name('controllers.booking.edit');
            Route::delete('{booking}', [AtcController::class, 'destroy'])->name('controllers.booking.delete');
            Route::put('{booking}', [AtcController::class, 'update'])->name('controllers.booking.update');
            Route::get('create', [AtcController::class, 'create'])->name('controllers.booking.create');
            Route::post('', [AtcController::class, 'store'])->name('controllers.booking.store');
            Route::get('', [AtcController::class, 'index'])->name('controllers.booking.index');
        });

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('controllers.feedback');
        Route::post('/atcfeedback/submit', [FeedbackController::class, 'store'])->name('controllers.feedback.submit');
    });
