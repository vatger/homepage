<?php

use App\Http\Controllers\HelpController;

Route::prefix('help')->group(function () {
    Route::get('/faq', [HelpController::class, 'faq'])->name('help.faq');
});
