<?php

use App\Http\Controllers\Administration\Tech\ApiLogController;
use App\Http\Controllers\Administration\Tech\BackendController;
use App\Http\Controllers\Administration\Tech\FailedJobController;
use App\Http\Controllers\Administration\Tech\SyslogController;
use App\Http\Controllers\Administration\Tech\TechController;
use Illuminate\Support\Facades\Route;

Route::prefix('tech')->group(function () {
    Route::prefix('jobs')->group(function () {
        Route::get('', [FailedJobController::class, 'index'])->name('administration.tech.jobs');
    });

    Route::prefix('syslog')->group(function () {
        Route::get('/', [SyslogController::class, 'index'])->name('administration.tech.syslog');
    });

    Route::prefix('apilog')->group(function () {
        Route::get('/', [ApiLogController::class, 'index'])->name('administration.tech.apilog');
    });

    Route::prefix('backend')->group(function () {
        Route::get('/', [BackendController::class, 'index'])->name('administration.tech.backend');
    });

    Route::prefix('management')->group(function () {
        Route::get('/', [TechController::class, 'management'])->name('administration.tech.management');
    });
});
