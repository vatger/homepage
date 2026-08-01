<?php

use App\Livewire\PolicyListPage;
use App\Livewire\PolicyPage;

Route::livewire('/policies', PolicyListPage::class)->name('policy-list');
Route::livewire('/policies/{policy_id}', PolicyPage::class)->name('policies');

Route::redirect('/gdpr', '/policies/gdpr')->name('gdpr');
Route::redirect('/datenschutz', '/policies/gdpr');
Route::redirect('/imprint', '/policies/imprint')->name('imprint');
Route::redirect('/impressum', '/policies/imprint');
Route::redirect('/terms', '/policies/termsofuse')->name('terms');
Route::redirect('/satzung', '/policies/satzung')->name('satzung');
