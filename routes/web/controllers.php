<?php

use Illuminate\Support\Facades\Route;

Route::prefix('controllers')
    ->middleware(['auth', 'banned'])
    ->group(function () {
        Route::prefix('booking')->group(function () {
            Route::get('', function () {
                return view('pages.atc-booking');
            })->name('controllers.booking');
        });
    });
