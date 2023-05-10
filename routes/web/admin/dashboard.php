<?php

use App\Http\Controllers\Administration\AdministrationPagesController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [AdministrationPagesController::class, 'index'])->name('administration.dashboard');
