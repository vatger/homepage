<?php


use App\Livewire\Administration\Tech\ApilogPage;
use App\Livewire\Administration\Tech\JoblogPage;
use App\Livewire\Administration\Tech\SyslogPage;
use App\Livewire\Administration\Tech\GdprRemovalsLogPage;
use Illuminate\Support\Facades\Route;

Route::prefix('tech')->group(function () {
    Route::get('gdpr-log', GdprRemovalsLogPage::class)->name('administration.tech.gdpr');

    Route::get('job-log', JoblogPage::class)->name('administration.tech.jobs');

    Route::get('sys-log', SyslogPage::class)->name('administration.tech.syslog');

    Route::get('api-log', ApilogPage::class)->name('administration.tech.apilog');

    //Route::prefix('backend')->group(function () {
    //    Route::get('/', [BackendController::class, 'index'])->name('administration.tech.backend');
    //});

    //Route::prefix('management')->group(function () {
    //    Route::get('/', [TechController::class, 'management'])->name('administration.tech.management');
    //});
});
