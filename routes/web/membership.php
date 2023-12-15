<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('members')
    ->middleware(['auth', 'banned', 'check-terms', 'staff_data_protection'])
    ->group(function () {
        Route::get('/profile', \App\Livewire\Profile\MembershipPage::class)->name('member.profile');
        Route::get('/profile/notifications', function () {
            return redirect()->route('member.profile', ['tab' => 'notifications']);
        })->name('member.profile.notifications');

        Route::get('banned', function (Request $request) {
            return view('pages.banned')->with('ban', Auth::user()->current_ban);
        })
            ->withoutMiddleware('banned')
            ->name('member.banned');
        Route::get('/sdp', \App\Livewire\Administration\StaffDataProtection::class)
            ->withoutMiddleware('staff_data_protection')
            ->name('administration.sdp');
    });
