<?php

use App\Http\Controllers\Event\EventPagesController;
use App\Http\Controllers\Event\EventroutesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
    Route::get('view/{eventId}', [EventPagesController::class, 'view'])->name('view');
});

Route::middleware(['auth', 'banned'])
    ->prefix('eventroutes')
    ->as('eventroutes.')
    ->group(function () {
        Route::get('', [EventroutesController::class, 'info'])->name('info');
        Route::get('list', [EventroutesController::class, 'routes'])->name('routes');
        Route::get('{eventroute}', [EventroutesController::class, 'view'])->name('view');
        Route::get('{eventroute}/signup', [EventroutesController::class, 'signupEventRoute'])->name('signup');
    });
