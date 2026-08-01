<?php

use App\Livewire\AerodromeListPage;
use App\Livewire\AerodromePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('cookie.consent')->group(function () {
    Route::get('aerodrome/{icao}', function (Request $request) {
        return redirect()->route('pilots.aerodromes.view', ['icao' => $request->route('icao')]);
    })->where([
        'icao' => '[a-zA-Z]{4}',
    ]);

    Route::group(['prefix' => 'pilots', 'as' => 'pilots.'], function () {
        Route::get('aerodrome/{icao}', function (Request $request) {
            return redirect()->route('pilots.aerodromes.view', ['icao' => $request->route('icao')]);
        })->where([
            'icao' => '[a-zA-Z]{4}',
        ]);

        Route::group(['prefix' => 'aerodromes', 'as' => 'aerodromes.'], function () {
            Route::livewire('/{icao}', AerodromePage::class)->name('view');
            Route::livewire('/', AerodromeListPage::class)->name('viewall');
        });
    });
});
