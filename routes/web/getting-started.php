<?php

use App\Livewire\GettingStartedPage;
use Illuminate\Support\Facades\Route;

Route::prefix('getting-started')->group(function () {
    Route::livewire('/', GettingStartedPage::class)->name('getting-started');
});
