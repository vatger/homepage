<?php

use App\Http\Controllers\Administration\Content\MediaController;
use App\Http\Controllers\Administration\Content\PartnerController;
use App\Http\Controllers\Administration\Content\ShortLinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('content')->group(function () {
    Route::prefix('media')->group(function () {
        Route::get('{mediaFilePath}', [MediaController::class, 'show'])->name('administration.content.media.show');
        Route::get('', [MediaController::class, 'index'])->name('administration.content.media');
    });

    Route::prefix('url')->group(function () {
        Route::get('/', [ShortLinkController::class, 'index'])->name('administration.content.urls');
    });

    Route::prefix('partner')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('administration.content.partners');
    });
});
