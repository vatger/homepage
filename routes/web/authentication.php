<?php

use App\Http\Controllers\ConnectController;
use App\Http\Controllers\GithubOauthController;
use App\Livewire\PolicyCheckPage;
use App\Models\Membership\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return redirect(route('vatsim.authentication.connect.login'));
})->name('login');

Route::group([
    'prefix' => 'authentication',
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

    Route::livewire('check_terms', PolicyCheckPage::class)
        ->name('check-terms')
        ->middleware('auth');

    Route::post('check_terms/{policyId}', function (Request $request, string $policyId) {
        $settings = UserSetting::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $settings->agreeTo($policyId, $request->string('decision')->value() === 'decline');

        return back();
    })->name('check-terms.update')->middleware('auth');

    Route::prefix('github')->group(function () {
        Route::get('link', [GithubOauthController::class, 'link'])
            ->name('github.oauth.link');

        Route::get('callback', [GithubOauthController::class, 'callback'])
            ->name('github.oauth.callback');
    });
});
