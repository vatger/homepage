<?php

use App\Http\Controllers\PagesController;

Route::get('/gdpr', [PagesController::class, 'gdpr'])->name('gdpr');

Route::get('/datenschutz', [PagesController::class, 'gdpr']);

Route::get('/imprint', [PagesController::class, 'imprint'])->name('imprint');

Route::get('/impressum', [PagesController::class, 'imprint']);

Route::get('/terms', [PagesController::class, 'terms'])->name('terms');

Route::get('/satzung', [PagesController::class, 'satzung'])->name('satzung');
