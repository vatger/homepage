<?php

use App\Http\Controllers\ConnectController;
use App\Http\Controllers\GithubOauthController;
use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return redirect(route('vatsim.authentication.connect.login'));
})->name('login');

Route::group([
    'prefix' => 'authentication',
    'middleware' => ['cookie.redirect'],
    'excluded_middleware' => ['check-terms'],
], function () {

    Route::prefix('connect')->group(function () {
        Route::get('login', [ConnectController::class, 'login'])
            ->name('vatsim.authentication.connect.login');

        Route::get('callback', [ConnectController::class, 'callback'])
            ->name('vatsim.authentication.connect.callback');

        Route::get('logout', [ConnectController::class, 'logout'])
            ->name('vatsim.authentication.connect.logout');
    });

    Route::get('check_terms', \App\Livewire\PolicyCheckPage::class)
        ->name('check-terms')
        ->middleware('auth');

    Route::prefix('github')->group(function () {
        Route::get('link', [GithubOauthController::class, 'link'])
            ->name('github.oauth.link');

        Route::get('callback', [GithubOauthController::class, 'callback'])
            ->name('github.oauth.callback');
    });
});
