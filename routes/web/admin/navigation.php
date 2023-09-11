<?php

use App\Livewire\Administration\Nav\AerodromeListPage;
use App\Livewire\Administration\Nav\AerodromePage;
use App\Livewire\Administration\Nav\StationListPage;
use Illuminate\Support\Facades\Route;

Route::prefix('navigation')->group(function () {
    Route::get('', function () {
        return null;
    })->name('administration.navigation');

    Route::get('stations', StationListPage::class)->name('administration.navigation.stations');

    Route::prefix('aerodromes')->group(function () {
        Route::get('', AerodromeListPage::class)->name('administration.navigation.aerodromes');
        Route::get('{aerodrome}', AerodromePage::class)->name('administration.navigation.aerodromes.view');
    });
});
