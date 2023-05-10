<?php

use App\Http\Controllers\Administration\Membership\GroupPagesController;
use App\Http\Controllers\Administration\Membership\MembershipPagesController;
use App\Http\Controllers\Administration\Membership\RoleController;
use App\Http\Controllers\Administration\Membership\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('membership')->group(function () {
    Route::prefix('groups')->group(function () {
        Route::get('{role}/togglePermission/{permission}', [RoleController::class, 'togglePermission'])->name(
            'administration.membership.group.togglepermission',
        );
        Route::delete('{role}/users', [RoleController::class, 'removeUser'])->name('administration.membership.group.users');
        Route::post('{role}/users', [RoleController::class, 'addUser'])->name('administration.membership.group.users');
        Route::get('{role}', [RoleController::class, 'show'])->name('administration.membership.group.view');
        Route::get('/', [GroupPagesController::class, 'index'])->name('administration.membership.groups');

        Route::post('/create', [GroupPagesController::class, 'createRole'])->name('administration.membership.group.create');
        Route::get('/{role}/remove', [GroupPagesController::class, 'removeRole'])->name('administration.membership.group.remove');
    });

    Route::prefix('members')->group(function () {
        Route::get('/', [MembershipPagesController::class, 'index'])->name('administration.membership.users');
        Route::get('/{user?}', [UserController::class, 'show'])->name('administration.membership.user.view');
    });
});
