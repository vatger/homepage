<?php

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DiscordApiController;
use App\Http\Controllers\Api\MoodleController;
use App\Http\Controllers\Api\SolosApiController;
use App\Http\Controllers\Api\TeamspeakApiController;
use App\Http\Controllers\Api\TestApiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VATEUDCoreContoller;
use App\Http\Controllers\Api\VatsimWebhookController;
use App\Http\Middleware\ApiJsonResponse;
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
Route::get('booking/ical/{id}/{token}/calendar.ics', [BookingController::class, 'ical'])->withoutMiddleware(ApiJsonResponse::class)->name('api.booking.ical');
Route::get('booking/{start?}/{end?}', [BookingController::class, 'index']);

Route::get('teamspeak/{cid}', [TeamspeakApiController::class, 'ids']);

Route::get('discord/{cid}', [DiscordApiController::class, 'find_member']);

Route::get('solos/{cid}', [SolosApiController::class, 'find_member']);

Route::get('vateud/roster', [VATEUDCoreContoller::class, 'roster_controller']);

Route::post('board', [BoardController::class, 'create']);

Route::post('vatsim/webhook', [VatsimWebhookController::class, 'post']);

Route::get('moodle/user/{cid}', [MoodleController::class, 'find_user']);
Route::get('moodle/quiz/{cmid}/user/{cid}/attempts', [MoodleController::class, 'find_quiz_attempts']);
Route::get('moodle/quiz/{cmid}/user/{cid}/override/attempts/{attempts}', [MoodleController::class, 'set_overrides']);
Route::get('moodle/course/{course_id}', [MoodleController::class, 'find_course']);
Route::get('moodle/course/{course_id}/user/{cid}/completion', [MoodleController::class, 'find_course_completion']);
Route::get('moodle/course/{course_id}/user/{cid}/enrol', [MoodleController::class, 'enrol_user']);

Route::get('moodle/activity/{cmid}/user/{cid}/completion', [MoodleController::class, 'find_activity_completion']);

Route::get('test', [TestApiController::class, 'test']);

Route::post('user/{cid}/send_notification', [UserController::class, 'send_notification']);

// Route::get('bookstack', [BookstackApiController::class, 'bookstack']);

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
