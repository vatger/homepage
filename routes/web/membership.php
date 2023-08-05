<?php

use App\Http\Controllers\User\Profile\ProfileController;
use App\Http\Controllers\User\Regionalgroup\RegionalgroupController;
use App\Http\Controllers\User\Regionalgroup\RequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('members')
    ->middleware(['auth', 'banned'])
    ->group(function () {
        Route::get('/profile', \App\Http\Livewire\Profile\MembershipPage::class)->name('member.profile');
        Route::get('/profile/notifications', [ProfileController::class, 'getNotificationsPaginated'])->name('member.profile.notifications');

        Route::prefix('regionalgroup')->group(function () {
            Route::delete('', [RegionalgroupController::class, 'delete'])->name('member.regionalgroup.delete');
            Route::get('{regionalgroup}', [RegionalgroupController::class, 'show'])->name('member.regionalgroup.view');

            Route::prefix('request')->group(function () {
                Route::delete('', [RequestController::class, 'delete'])->name('member.regionalgroup.request');
                Route::post('', [RequestController::class, 'create'])->name('member.regionalgroup.request');
            });
        });

        Route::get('banned', function (Request $request) {
            return view('homepage.static.banned');
        })
            ->withoutMiddleware('auth')
            ->withoutMiddleware('standings')
            ->name('member.banned');
    });
