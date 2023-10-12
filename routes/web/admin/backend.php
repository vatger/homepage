<?php

use App\Http\Controllers\Administration\Tech\BackendController;
use App\Http\Controllers\Administration\Tech\FailedJobController;
use App\Http\Controllers\Administration\Tech\TechController;
use App\Livewire\Administration\Tech\ApilogPage;
use App\Livewire\Administration\Tech\JoblogPage;
use App\Livewire\Administration\Tech\SyslogPage;
use Illuminate\Support\Facades\Route;

Route::prefix('tech')->group(function () {
    Route::get('joblog', JoblogPage::class)->name('administration.tech.jobs');

    Route::get('syslog', SyslogPage::class)->name('administration.tech.syslog');

    Route::get('apilog', ApilogPage::class)->name('administration.tech.apilog');

    //Route::prefix('backend')->group(function () {
    //    Route::get('/', [BackendController::class, 'index'])->name('administration.tech.backend');
    //});

    //Route::prefix('management')->group(function () {
    //    Route::get('/', [TechController::class, 'management'])->name('administration.tech.management');
    //});
});
