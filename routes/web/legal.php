<?php

use App\Http\Controllers\PagesController;
use App\Livewire\PolicyPage;

Route::get('/policies/{policy_id}', PolicyPage::class)->name('policies');

Route::redirect('/gdpr', '/policies/gdpr')->name('gdpr');
Route::redirect('/datenschutz', '/policies/gdpr');
Route::redirect('/imprint', '/policies/imprint')->name('imprint');
Route::redirect('/impressum', '/policies/imprint')->name('imprint');
Route::redirect('/terms', '/policies/termsofuse')->name('terms');
Route::redirect('/satzung', '/policies/satzung')->name('satzung');
