<?php

use App\Livewire\GettingStartedPage;
use Illuminate\Support\Facades\Route;

Route::prefix("getting-started")->group(function () {
    Route::get("/", GettingStartedPage::class)->name("getting-started");
});
