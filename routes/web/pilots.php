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

    /*

     Route::prefix('livemap')->group(function () {
        Route::get('atc/{callsign}', [LivemapController::class, 'getControllerDetails'])->name('pilots.livemap.atc.details');
        Route::get('atc', [LivemapController::class, 'getConnectedAtc'])->name('pilots.livemap.atc');
        Route::get('pilots', [LivemapController::class, 'getConnectedPilots'])->name('pilots.livemap.pilots');
        Route::get('sector/{callsign}', [LivemapController::class, 'getSector'])->name('pilots.livemap.sector');
        Route::get('', [LivemapController::class, 'index'])->name('pilots.livemap');
    });

    */
});
