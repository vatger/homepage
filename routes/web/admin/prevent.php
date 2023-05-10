<?php

use App\Http\Controllers\Administration\Prevent\EventroutesController;
use Illuminate\Support\Facades\Route;

Route::prefix('prevent')->group(function () {
    Route::prefix('route')->group(function () {
        Route::delete('{eventRoute}/leg', [EventroutesController::class, 'deleteLeg'])->name('administration.prevent.route.leg.delete');
        Route::post('{eventRoute}/leg', [EventroutesController::class, 'storeLeg'])->name('administration.prevent.route.leg.store');
        Route::get('{eventRoute}', [EventroutesController::class, 'show'])->name('administration.prevent.route.show');
        Route::post('', [EventroutesController::class, 'store'])->name('administration.prevent.route.store');
        Route::get('', [EventroutesController::class, 'index'])->name('administration.prevent.route');
        Route::delete('{eventRoute}', [EventroutesController::class, 'delete'])->name('administration.prevent.route.delete');
        Route::get('{eventRoute}/accounts', [EventroutesController::class, 'getaccounts'])->name('administration.prevent.route.getaccounts');
    });
    Route::prefix('routedev')->group(function () {
        Route::get('', [EventroutesController::class, 'index1'])->name('administration.prevent.routedev');
        Route::get('{eventRoute}', [EventroutesController::class, 'showdev'])->name('administration.prevent.routedev.show');
    });
});
