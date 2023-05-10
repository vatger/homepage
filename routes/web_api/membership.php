<?php

use App\Http\Controllers\Administration\Membership\GroupPagesController;
use App\Http\Controllers\Administration\Membership\MembershipPagesController;
use App\Http\Controllers\Administration\Membership\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller\FeedbackController;
use App\Http\Controllers\User\Profile\ProfileSettingsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('administration')->group(function () {
        Route::prefix('membership')->group(function () {
            //Route::get('/getpaginated', [MembershipPagesController::class, 'getUsersPaginated'])->name('api.administration.membership.members.getpaginated');
            //Route::get('/getsearch', [MembershipPagesController::class, 'getUsersSearch'])->name('api.administration.membership.members.search');

            Route::get('/teamspeak', [MembershipPagesController::class, 'getUserTeamspeakDetails'])->name(
                'api.administration.membership.member.teamspeak',
            );
        });

        Route::prefix('group')->group(function () {
            Route::get('/getpaginated', [GroupPagesController::class, 'getGroupsPaginated'])->name(
                'api.administration.membership.groups.getpaginated',
            );
            Route::get('/getsearch', [GroupPagesController::class, 'getGroupsSearch'])->name('api.administration.membership.groups.search');

            Route::get('/removeuser', [RoleController::class, 'removeUser'])->name('api.administration.membership.groups.removeuser');
            Route::get('/adduser', [RoleController::class, 'addUser'])->name('api.administration.membership.groups.adduser');
            Route::get('/toggleperm', [RoleController::class, 'togglePermission'])->name('api.administration.membership.groups.toggleperm');
        });
    });

    Route::prefix('membership')->group(function () {
        Route::put('setLanguage', [ProfileSettingsController::class, 'submitLanguage'])->name('api.membership.settings.setLanguage');
        Route::post('submitAppearanceSettings', [ProfileSettingsController::class, 'submitAppearance'])->name(
            'api.membership.settings.submitappearance',
        );

        Route::post('registerteamspeak', [ProfileSettingsController::class, 'createTeamspeakRegistration'])->name(
            'api.membership.teamspeak.submitregistration',
        );
    });

    Route::get('checkuser', [FeedbackController::class, 'checkDoesUserExist'])->name('api.user.check');
});
