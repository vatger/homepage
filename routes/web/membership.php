<?php

use App\Http\Controllers\MembershipController;
use App\Livewire\Administration\StaffDataProtection;
use App\Livewire\Profile\MembershipPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('members')
    ->middleware(['auth', 'pending_removal', 'banned', 'check-terms', 'staff_data_protection'])
    ->group(function () {
        Route::get('/profile', MembershipPage::class)->name('member.profile');
        Route::get('/profile/notifications', function () {
            return redirect()->route('member.profile', ['tab' => 'notifications']);
        })->name('member.profile.notifications');

        Route::get('banned', [MembershipController::class, 'banned'])
            ->withoutMiddleware('banned')
            ->name('member.banned');

        Route::get('gdpr-removal-pending', [MembershipController::class, 'pending_removal'])
            ->withoutMiddleware('pending_removal')
            ->name('member.removal-pending');

        Route::get('gdpr-removal-pending/cancel', [MembershipController::class, 'pending_removal_cancel'])
            ->withoutMiddleware('pending_removal')
            ->name('member.removal-pending.cancel');

        Route::get('refresh', [MembershipController::class, 'refresh'])
            ->withoutMiddleware('banned')
            ->name('member.refresh');

        Route::get('/sdp', StaffDataProtection::class)
            ->withoutMiddleware(['staff_data_protection'])
            ->name('administration.sdp');

    });
