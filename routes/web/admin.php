<?php

use App\Http\Controllers\Administration\AdministrationPagesController;
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
    ->middleware(['cookie.consent', 'auth'])
    ->group(function () {
        Route::get('/dashboard', [AdministrationPagesController::class, 'index'])->name('administration.dashboard');
        Route::get('/membership/members', \App\Livewire\Administration\Members::class)->name('administration.members');
        Route::get('/membership/members/{user}', \App\Livewire\Administration\Member::class)->name('administration.member');

        require_once 'admin/content.php';

        require_once 'admin/event.php';

        //require_once 'admin/membership.php';

        require_once 'admin/navigation.php';

        require_once 'admin/prevent.php';

        require_once 'admin/regionalgroup.php';

        require_once 'admin/backend.php';
    });
