<?php

use App\Http\Controllers\Administration\AdministrationPagesController;
use App\Livewire\Administration\EmailPage;
use App\Livewire\Administration\MemberListPage;
use App\Livewire\Administration\MemberPage;
use App\Livewire\Administration\SurveyPage;
use App\Livewire\Administration\TeamListPage;
use App\Livewire\Administration\TeamPage;
use Illuminate\Support\Facades\Route;

/**
 * Define all routes required for administration
 * via web interface / guard here
 *
 * All routes defined here will only be accessable
 * to users who have the permission 'administration.access' assigned.
 * This permission is check via administration-access gate.
 */
Route::prefix('administration')
    //->middleware(['cookie.consent', 'auth', 'standings', 'can:administration-access'])
    ->middleware(['cookie.consent', 'auth', 'staff_data_protection'])
    ->group(function () {
        Route::get('/dashboard', [AdministrationPagesController::class, 'index'])->name('administration.dashboard');
        Route::get('/membership/members', MemberListPage::class)->name('administration.members');
        Route::get('/membership/members/{user}', MemberPage::class)->name('administration.member');

        Route::get('/membership/teams', TeamListPage::class)->name('administration.teams');
        Route::get('/membership/teams/{team}', TeamPage::class)->name('administration.team');

        Route::get('/survey', SurveyPage::class)->name('administration.survey');
        Route::get('/email', EmailPage::class)->name('administration.email');

        require_once 'admin/content.php';

        require_once 'admin/event.php';

        //require_once 'admin/membership.php';

        require_once 'admin/navigation.php';

        require_once 'admin/prevent.php';

        require_once 'admin/regionalgroup.php';

        require_once 'admin/backend.php';
    });
