<?php

use App\Livewire\AerodromeListPage;
use App\Livewire\AerodromePage;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware('cookie.consent')->group(function () {
    Route::get('aerodrome/{icao}', function (Request $request) {
        return redirect()->route('pilots.aerodromes.view', ['icao' => $request->route('icao')]);
    })->where([
        'icao' => '[a-zA-Z]{4}',
    ]);

    Route::group(['prefix' => 'pilots', 'as' => 'pilots.'], function () {
        Route::group(['prefix' => 'aerodromes', 'as' => 'aerodromes.'], function () {
            Route::get('/{icao}', AerodromePage::class)->name('view');
            Route::get('/', AerodromeListPage::class)->name('viewall');
        });
    });
});
