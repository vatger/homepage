<?php

use App\Http\Controllers\Administration\Content\PartnerController;
use App\Http\Controllers\Administration\Content\ShortLinkController;
use App\Http\Controllers\Administration\Content\MediaController;
use App\Http\Controllers\Administration\Tech\SyslogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('content')
        ->name('api.administration.content.')
        ->group(function () {
            Route::prefix('media')->group(function () {
                Route::get('getpaginated', [MediaController::class, 'getMediaPaginated'])->name('media.getpaginated');
                //Route::get('getsearch', [MediaController::class, 'getMediaSearch'])->name('media.search'); does not exist

                Route::delete('{mediaFile}', [MediaController::class, 'delete'])->name('media.delete');
                Route::patch('{mediaFile}', [MediaController::class, 'update'])->name('media.update');
                Route::post('create', [MediaController::class, 'store'])->name('media.store');
            });

            Route::prefix('url')->group(function () {
                Route::get('/getpaginated', [ShortLinkController::class, 'getUrlsPaginated'])->name('url.getpaginated');
                Route::get('/getsearch', [ShortLinkController::class, 'getUrlSearch'])->name('url.search');

                Route::post('/create', [ShortLinkController::class, 'createShortLink'])->name('url.create');
                Route::post('/remove', [ShortLinkController::class, 'removeShortLink'])->name('url.remove');
                Route::patch('/toggleactivity', [ShortLinkController::class, 'toggleActivity'])->name('url.toggleActive');
            });

            Route::prefix('partner')->group(function () {
                Route::get('/getpaginated', [PartnerController::class, 'getPartnersPaginated'])->name('partner.getpaginated');
                Route::get('/getsearch', [PartnerController::class, 'getPartnerSearch'])->name('partner.search');

                Route::get('/find', [PartnerController::class, 'findPartnerById'])->name('partner.find');
                Route::post('/create', [PartnerController::class, 'submitPartner'])->name('partner.submit');

                Route::get('/delete', [PartnerController::class, 'removePartner'])->name('partner.remove');
            });
        });
});
