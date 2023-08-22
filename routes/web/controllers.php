<?php

use App\Http\Controllers\Controller\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::prefix('controllers')
    ->middleware(['auth', 'banned'])
    ->group(function () {
        Route::prefix('booking')->group(function () {
            Route::get('', function () {
                return view('pages.atc-booking');
            })->name('controllers.booking');
        });

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('controllers.feedback');
        Route::post('/atcfeedback/submit', [FeedbackController::class, 'store'])->name('controllers.feedback.submit');
    });
