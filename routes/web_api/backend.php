<?php

use App\Http\Controllers\Administration\Tech\SyslogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('tech')->group(function () {
        Route::prefix('syslog')->group(function () {
            Route::get('/getpaginated', [SyslogController::class, 'getSyslogPaginated'])->name('api.administration.tech.syslog.getpaginated');
            Route::get('/getsearch', [SyslogController::class, 'getSyslogSearch'])->name('api.administration.tech.syslog.search');

            Route::get('/getinfo', [SyslogController::class, 'getSyslogInfo'])->name('api.administration.tech.syslog.getinfo');
        });
    });
});
