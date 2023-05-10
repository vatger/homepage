<?php

use App\Http\Controllers\Administration\Navigation\AerodromeController;
use App\Http\Controllers\Administration\Navigation\StationController;
use App\Http\Controllers\Administration\Navigation\NavigationPagesController;
use App\Http\Controllers\Administration\Navigation\RunwayController;
use App\Http\Controllers\Administration\Navigation\ChartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('navigation')->group(function () {
        Route::prefix('aerodromes')->group(function () {
            Route::get('/getpaginated', [AerodromeController::class, 'getAerodromesPaginated'])->name(
                'api.administration.navigation.aerodromes.getpaginated',
            );
            Route::get('/getsearch', [AerodromeController::class, 'getAerodromesSearch'])->name('api.administration.navigation.aerodromes.search');

            Route::post('/runway', [RunwayController::class, 'store'])->name('api.administration.navigation.aerodromes.runways.store');
        });

        Route::prefix('stations')->group(function () {
            Route::get('/getpaginated', [StationController::class, 'getStationsPaginated'])->name(
                'api.administration.navigation.stations.getpaginated',
            );
            Route::get('/getsearch', [StationController::class, 'getStationsSearch'])->name('api.administration.navigation.stations.search');
        });

        Route::prefix('charts')->group(function () {
            Route::get('/getpaginated', [ChartController::class, 'getChartsPaginated'])->name('api.administration.navigation.charts.getpaginated');
            Route::get('/getsearch', [ChartController::class, 'getChartsSearch'])->name('api.administration.navigation.charts.search');
        });
    });
});
