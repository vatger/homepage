<?php

use App\Http\Controllers\Event\EventPagesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'events', 'as' => 'events.',  'middleware' => 'cookie.consent'], function () {

    Route::get('view/{id}', [EventPagesController::class, 'view'])->name('view');

});

Route::group(['prefix' => 'events', 'as' => 'events.',  'middleware' => 'cookie.redirect'], function () {

    Route::get('calendar', [EventPagesController::class, 'calendar'])->name('calendar');

});
