<?php

use App\Http\Controllers\Administration\AdministrationPagesController;
use App\Livewire\Administration\EmailPage;
use App\Livewire\Administration\MemberListPage;
use App\Livewire\Administration\MemberPage;
use App\Livewire\Administration\Nav\AerodromeListPage;
use App\Livewire\Administration\Nav\AerodromePage;
use App\Livewire\Administration\Nav\StationListPage;
use App\Livewire\Administration\SurveyPage;
use App\Livewire\Administration\TeamListPage;
use App\Livewire\Administration\TeamPage;
use App\Livewire\Administration\Tech\ApilogPage;
use App\Livewire\Administration\Tech\GdprRemovalsLogPage;
use App\Livewire\Administration\Tech\JoblogPage;
use App\Livewire\Administration\Tech\OpenIDConnectPage;
use App\Livewire\Administration\Tech\SyslogPage;
use Illuminate\Support\Facades\Route;

/**
 * Define all routes required for administration
 * via web interface / guard here
 */
Route::prefix('administration')
    ->middleware(['auth', 'staff_data_protection'])
    ->group(function () {
        Route::get('/dashboard', [AdministrationPagesController::class, 'index'])->name('administration.dashboard');
        Route::livewire('/membership/members', MemberListPage::class)->name('administration.members');
        Route::livewire('/membership/members/{user}', MemberPage::class)->name('administration.member');

        Route::livewire('/membership/teams', TeamListPage::class)->name('administration.teams');
        Route::livewire('/membership/teams/{team}', TeamPage::class)->name('administration.team');

        Route::livewire('/survey', SurveyPage::class)->name('administration.survey');
        Route::livewire('/email', EmailPage::class)->name('administration.email');

        Route::prefix('navigation')->group(function () {
            Route::get('', function () {
                return null;
            })->name('administration.navigation');

            Route::livewire('stations', StationListPage::class)->name('administration.navigation.stations');

            Route::prefix('aerodromes')->group(function () {
                Route::livewire('', AerodromeListPage::class)->name('administration.navigation.aerodromes');
                Route::livewire('{aerodrome}', AerodromePage::class)->name('administration.navigation.aerodromes.view');
            });
        });

        Route::prefix('tech')->group(function () {
            Route::livewire('gdpr-log', GdprRemovalsLogPage::class)->name('administration.tech.gdpr');

            Route::livewire('job-log', JoblogPage::class)->name('administration.tech.jobs');

            Route::livewire('sys-log', SyslogPage::class)->name('administration.tech.syslog');

            Route::livewire('api-log', ApilogPage::class)->name('administration.tech.apilog');

            Route::livewire('openid-connect', OpenIDConnectPage::class)->name('administration.tech.openidconnect');
        });

    });
