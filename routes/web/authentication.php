<?php

use App\Http\Controllers\Authentication\ConnectController;
use Illuminate\Support\Facades\Route;

Route::prefix('authentication')
    ->middleware('cookie.consent')
    ->group(function () {
        Route::prefix('connect')->group(function () {
            Route::get('login', [ConnectController::class, 'login'])->name('vatsim.authentication.connect.login');
            Route::get('callback', [ConnectController::class, 'callback'])->name('vatsim.authentication.connect.callback');
            Route::get('logout', [ConnectController::class, 'logout'])->name('vatsim.authentication.connect.logout');
            Route::post('local', [ConnectController::class, 'localLogin']);
            Route::get('local', [ConnectController::class, 'local'])->name('vatsim.authentication.connect.local');
            Route::get('failed', [ConnectController::class, 'failed'])->name('vatsim.authentication.connect.failed');
        });

        Route::get('check_terms', function () {
            $user = Auth::user();
            $user->loadMissing(['settings', 'vatgerDetails', 'vatsimDetails']);
            return $user;
        })
            ->name('check-terms')
            ->middleware('auth');

        Route::get('test', function () {
            $user = Auth::user();
            \App\Libraries\Membership\MembershipLibrary::seen($user);
            $user->loadMissing(['settings', 'vatgerDetails', 'vatsimDetails']);
            return $user;
        })
            ->name('check-terms')
            ->middleware('auth');
    });
