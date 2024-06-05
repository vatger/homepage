<?php

use App\Http\Controllers\Vatsim\QueryVatsimAPIController;
use App\Http\Controllers\Pilot\Aerodrome\AerodromeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
|  WEB API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "web_api" middleware group. Use only for website ajax requests.
| Other API requests shall be handled by api.php
|
*/
Route::get('queryevents/banner/{id}', [QueryVatsimAPIController::class, 'getBanner'])->name('api.getBanner');
Route::get('queryevents/{count?}', [QueryVatsimAPIController::class, 'loadEvents'])->name('api.loadEvents');
Route::get('queryevent', [QueryVatsimAPIController::class, 'loadSingleEvent'])->name('api.loadEvent');
Route::get('activecontrollers/{icao}', [QueryVatsimAPIController::class, 'loadActiveAtc'])->name('api.loadActiveAtc');
Route::get('standstatus/{icao}', [AerodromeController::class, 'getStandStatus'])->name('api.aerodrome.standstatus');
Route::get('querymetar', [QueryVatsimAPIController::class, 'loadMetar'])->name('api.loadMetar');
