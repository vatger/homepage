<?php

use App\Http\Controllers\Administration\Regionalgroup\DashboardController;
use App\Http\Controllers\Administration\Regionalgroup\RequestController;
use App\Http\Controllers\Administration\Regionalgroup\StaffController;
use App\Http\Controllers\Administration\Regionalgroup\RegionalgroupController;

Route::prefix('regionalgroup')->group(function () {
    Route::prefix('{regionalgroup}/staff')->group(function () {
        Route::post('setChief', [StaffController::class, 'setChief'])->name('administration.regionalgroup.staff.chief');
        Route::post('setDeputy', [StaffController::class, 'setDeputy'])->name('administration.regionalgroup.staff.deputy');
    });

    Route::prefix('{regionalgroup}/navigators')->group(function () {
        Route::patch('update', [RegionalgroupController::class, 'updateNavigators'])->name('administration.regionalgroup.navigators.update');
    });

    Route::prefix('{regionalgroup}/request')->group(function () {
        Route::delete('{regionalgroupRequest}', [RequestController::class, 'delete'])->name('administration.regionalgroup.request.delete');
        Route::patch('{regionalgroupRequest}', [RequestController::class, 'update'])->name('administration.regionalgroup.request.update');
        Route::get('{regionalgroupRequest}', [RequestController::class, 'show'])->name('administration.regionalgroup.request.view');
    });

    Route::get('{regionalgroup}', [DashboardController::class, 'view'])->name('administration.regionalgroup.view');
    Route::get('', [DashboardController::class, 'index'])->name('administration.regionalgroup.index');
});
