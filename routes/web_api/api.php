<?php
use App\Http\Controllers\Vatsim\QueryVatsimAPIController;
use App\Http\Controllers\Pilot\Aerodrome\AerodromeController;
use Illuminate\Support\Facades\Route;

require_once 'euroscope.php';
require_once 'membership.php';
require_once 'pilots.php';
require_once 'booking.php';
require_once 'navigation.php';
require_once 'backend.php';
require_once 'content.php';

Route::get('queryevents/{count?}', [QueryVatsimAPIController::class, 'loadEvents'])->name('api.loadEvents');
//Route::get('queryevents/paginated', [QueryVatsimAPIController::class, 'loadEventsPaginated'])->name('api.loadEvents.paginated'); does not exist
Route::get('queryevent', [QueryVatsimAPIController::class, 'loadSingleEvent'])->name('api.loadEvent');
Route::get('activecontrollers/{icao}', [QueryVatsimAPIController::class, 'loadActiveAtc'])->name('api.loadActiveAtc');
Route::get('standstatus/{icao}', [AerodromeController::class, 'getStandStatus'])->name('api.aerodrome.standstatus');
Route::get('querymetar', [QueryVatsimAPIController::class, 'loadMetar'])->name('api.loadMetar');
