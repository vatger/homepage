<?php

use App\Http\Controllers\Administration\Navigation\AerodromeController;
use App\Http\Controllers\Administration\Navigation\StationController;
use App\Http\Controllers\Administration\Navigation\NavigationPagesController;
use App\Http\Controllers\Administration\Navigation\RunwayController;
use App\Http\Controllers\Administration\Navigation\ChartController;
use App\Libraries\EuroScope\SectorDataLibrary;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::prefix('navigation')->group(function () {
    Route::prefix('runways')->group(function () {
        Route::delete('{aerodrome}/{runway}', [RunwayController::class, 'delete'])->name('administration.navigation.runways.delete');
        Route::patch('{aerodrome}', [RunwayController::class, 'update'])->name('administration.navigation.runways.update');
        Route::post('{aerodrome}', [RunwayController::class, 'store'])->name('administration.navigation.runways.store');
    });

    Route::prefix('charts')->group(function () {
        Route::get('create', [ChartController::class, 'create'])->name('administration.navigation.charts.create');
        Route::delete('{chart}', [ChartController::class, 'destroy'])->name('administration.navigation.charts.delete');
        Route::get('{chart}', [ChartController::class, 'show'])->name('administration.navigation.charts.view');
        Route::post('', [ChartController::class, 'store'])->name('administration.navigation.charts.store');
        Route::get('', [ChartController::class, 'index'])->name('administration.navigation.charts');
    });

    Route::prefix('stations')->group(function () {
        Route::get('create', [StationController::class, 'create'])->name('administration.navigation.stations.create');

        Route::delete('{station}', [StationController::class, 'delete'])->name('administration.navigation.stations.delete');
        Route::patch('{station}', [StationController::class, 'update'])->name('administration.navigation.stations.update');
        Route::get('{station}', [StationController::class, 'show'])->name('administration.navigation.stations.view');
        Route::post('', [StationController::class, 'store'])->name('administration.navigation.stations.store');

        Route::get('', [StationController::class, 'index'])->name('administration.navigation.stations');
    });

    Route::prefix('aerodromes')->group(function () {
        Route::patch('{aerodrome}/updateStationOrder', [AerodromeController::class, 'updateStationOrder'])->name(
            'administration.navigation.aerodromes.updateStationOrder',
        );
        Route::post('{aerodrome}/stations', [AerodromeController::class, 'addStation'])->name('administration.navigation.aerodromes.station.add');
        Route::delete('{aerodrome}/charts', [AerodromeController::class, 'unassignChart'])->name(
            'administration.navigation.aerodromes.chart.unassign',
        );
        Route::post('{aerodrome}/charts', [AerodromeController::class, 'assignChart'])->name('administration.navigation.aerodromes.chart.assign');
        Route::patch('{aerodrome}/chartfox', [AerodromeController::class, 'toggleChartfox'])->name('administration.navigation.aerodromes.chartfox');
        Route::post('{aerodrome}', [AerodromeController::class, 'update'])->name('administration.navigation.aerodromes.update');
        Route::get('{aerodrome}', [AerodromeController::class, 'show'])->name('administration.navigation.aerodromes.view');
        Route::get('', [AerodromeController::class, 'index'])->name('administration.navigation.aerodromes');
    });

    Route::prefix('sectordata')->group(function () {
        Route::get('downloadCombined', function () {
            $zip = new ZipArchive();
            $fn = 'combined.zip';
            if ($zip->open(storage_path('app') . '/euroscope/sectorfiles/build/' . $fn, ZipArchive::CREATE) === true) {
                $files = \File::files(storage_path('app') . '/euroscope/sectorfiles/build');
                foreach ($files as $f => $p) {
                    if (Str::endsWith($p, ['.ese', '.sct'])) {
                        $zip->addFile($p, basename($p));
                    }
                }
                $zip->close();
            }
            return response()->download(storage_path('app') . '/euroscope/sectorfiles/build/' . $fn);
        });
    });

    Route::get('', [NavigationPagesController::class, 'index'])->name('administration.navigation');
});
