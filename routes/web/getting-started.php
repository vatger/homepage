<?php

use Illuminate\Support\Facades\Route;

Route::prefix("getting-started")->group(function () {
    Route::get("/", function () {
        return view("pages.getting-started");
    })->name("getting-started");
});
