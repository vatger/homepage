<?php

use App\Livewire\RestrictedPage;
use App\Livewire\S1Page;
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
Route::prefix('controllers')
    ->group(function () {
        Route::get('restricted', RestrictedPage::class)->name('controllers.restricted');
        Route::get('s1', S1Page::class)->name('controllers.s1');
        Route::get('required-courses', \App\Livewire\RequiredCoursesPage::class)->name('controllers.required-courses');
    });
