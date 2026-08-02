<?php

use App\Http\Controllers\Event\EventPagesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {

    Route::get('view/{id}', [EventPagesController::class, 'view'])->name('view');

});

Route::group(['prefix' => 'events', 'as' => 'events.',  'middleware' => 'cors'], function () {

    Route::get('calendar', [EventPagesController::class, 'calendar'])->name('calendar');

});
