<?php

use App\OpenApi\Controllers\ApiController;
use App\OpenApi\Controllers\MentorController;
use App\OpenApi\Controllers\NavigationController;
use App\OpenApi\Controllers\UserController;
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
Route::get('test', [ApiController::class, 'test']);

Route::get('book', [ApiController::class, 'bookstack']);

//Route::middleware('api_auth')->group(function () {
Route::get('user/{id}', [UserController::class, 'userShow']);
Route::get('user/{id}/regionalgroups', [UserController::class, 'userRegionalgroups']);
Route::get('user/{id}/mentor', [UserController::class, 'userMentor']);
Route::post('user/{id}/notification', [UserController::class, 'userNotification']);
//});

Route::get('mentors', [MentorController::class, 'listMentors']);

Route::get('stations', [NavigationController::class, 'stationList']);
Route::get('stations/{ident}', [NavigationController::class, 'stationView']);
