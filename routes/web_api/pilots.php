<?php

use App\Http\Controllers\Pilot\Aerodrome\AerodromeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

Route::prefix('aerodromes')->group(function () {
    Route::get('/getall/{includeInternational?}', [AerodromeController::class, 'getAllAerodromes'])->name('api.pilots.aerodromes.getall');
    Route::get('/getsearch', [AerodromeController::class, 'getAerodromesSearch'])->name('api.pilots.aerodromes.search');
});

Route::middleware('auth:sanctum')->group(function () {
    //  AUTHED API ROUTES
});
