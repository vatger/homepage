<?php

use App\Http\Controllers\Controller\FirstStepsController;
use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::prefix('getting-started')->group(function () {
    Route::get('/', [PagesController::class, 'getStarted'])->name('getting-started');
    Route::get('/atc', [FirstStepsController::class, 'index'])->name('getting-started.atc');
    Route::get('/pilot', [FirstStepsController::class, 'index1'])->name('getting-started.pilot');
});
