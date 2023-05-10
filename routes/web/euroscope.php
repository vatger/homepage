<?php

/**
 * Routes for EuroScope Tools
 */

use App\Http\Controllers\EuroScope\ScenarioController;
use App\Http\Controllers\EuroScope\SectorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::prefix('euroscope')->group(function () {
    /**
     * Routes for EuroScope Sim Sessions
     */
    Route::prefix('simsession')->group(function () {
        Route::get('download/{name}', [ScenarioController::class, 'download'])->name('euroscope.scenarios.download');
        Route::post('', [ScenarioController::class, 'store'])->name('euroscope.scenarios.store');
        Route::get('create', [ScenarioController::class, 'create'])->name('euroscope.scenarios.create');
        Route::get('{name}', [ScenarioController::class, 'show'])->name('euroscope.scenarios.show');
        Route::get('', [ScenarioController::class, 'index'])->name('euroscope.scenarios.index');
    });
    /**
     * Routes for combined sectorfile download
     */
    Route::prefix('sectorfile')->group(function () {
        Route::get('download', [SectorController::class, 'download'])->name('euroscope.sectorfile.download');
        Route::get('', [SectorController::class, 'index'])->name('euroscope.sectorfile.index');
    });
});
