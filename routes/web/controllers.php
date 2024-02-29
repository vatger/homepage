<?php

use App\Livewire\Restricted;
use App\Livewire\S1;
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

Route::get('restricted', Restricted::class)->name('redirect.restricted');
Route::get('s1', S1::class)->name('redirect.s1');
