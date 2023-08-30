<?php

use App\Http\Controllers\ConnectController;
use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return redirect(route('vatsim.authentication.connect.login'));
})->name('login');

Route::prefix('authentication')
    ->middleware('cookie.consent')
    ->group(function () {
        Route::prefix('connect')->group(function () {
            Route::get('login', [ConnectController::class, 'login'])->name('vatsim.authentication.connect.login');
            Route::get('callback', [ConnectController::class, 'callback'])->name('vatsim.authentication.connect.callback');
            Route::get('logout', [ConnectController::class, 'logout'])->name('vatsim.authentication.connect.logout');
            Route::get('failed', [ConnectController::class, 'failed'])->name('vatsim.authentication.connect.failed');
        });

        Route::get('test', function () {
            dd(Auth::user()->service_role_ids(\App\Models\Groups\ServiceRoleType::TeamspeakServergroup));
        });

        Route::get('check_terms', \App\Livewire\TermsPage::class)
            ->name('check-terms')
            ->middleware('auth');
    });
