<?php

use App\OpenApi\Controllers\BookingController;
use App\OpenApi\Controllers\BookstackApiController;
use App\OpenApi\Controllers\TeamspeakApiController;
use App\OpenApi\Controllers\TestApiController;
use App\OpenApi\Controllers\UserController;
use App\OpenApi\Middleware\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Use this to put non-frontend /
| non-web API routes.
|
*/
Route::get('booking/ical/{id}/{token}/calendar.ics', [BookingController::class, 'ical'])->withoutMiddleware(JsonResponse::class)->name('api.booking.ical');
Route::get('booking/{start?}/{end?}', [BookingController::class, 'index']);
Route::get('teamspeak/{cid}', [TeamspeakApiController::class, 'ids']);
Route::get('discord/{cid}', [\App\OpenApi\Controllers\DiscordApiController::class, 'find_member']);
Route::get('solos/{cid}', [\App\OpenApi\Controllers\SolosApiController::class, 'find_member']);
Route::get('vateud/roster', [\App\OpenApi\Controllers\VATEUDCoreContoller::class, 'roster_controller']);
Route::post('board', [\App\OpenApi\Controllers\BoardController::class, 'create']);
Route::post('vatsim/webhook', [\App\OpenApi\Controllers\VatsimWebhookController::class, 'post']);


Route::get('test', [TestApiController::class, 'test']);

Route::post('user/{cid}/send_notification', [UserController::class, 'send_notification']);



//Route::get('bookstack', [BookstackApiController::class, 'bookstack']);

/*
//Route::middleware('api_auth')->group(function () {
Route::get('user/{id}', [UserController::class, 'userShow']);
Route::get('user/{id}/regionalgroups', [UserController::class, 'userRegionalgroups']);
Route::get('user/{id}/mentor', [UserController::class, 'userMentor']);
Route::post('user/{id}/notification', [UserController::class, 'userNotification']);
//});

Route::get('mentors', [MentorController::class, 'listMentors']);

Route::get('stations', [NavigationController::class, 'stationList']);
Route::get('stations/{ident}', [NavigationController::class, 'stationView']);
*/
