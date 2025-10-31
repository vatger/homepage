<?php

use App\Livewire\Atc\RequiredCoursesPage;
use App\Livewire\Atc\RestrictedPage;
use App\Livewire\Atc\S1Page;
use App\Livewire\Atc\S1StationsPage;
use Illuminate\Support\Facades\Route;

Route::prefix('controllers')
    ->middleware(['cookie.consent', 'auth', 'pending_removal', 'banned'])
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
        Route::get('s1-stations', S1StationsPage::class)->name('controllers.s1-stations');
        Route::get('required-courses', RequiredCoursesPage::class)->name('controllers.required-courses');
    });
