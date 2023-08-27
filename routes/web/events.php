<?php

use App\Http\Controllers\Event\EventPagesController;
use App\Http\Controllers\Event\EventroutesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get('view/{id}', [EventPagesController::class, 'view'])->name('view');
});
