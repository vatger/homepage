<?php

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'seo' => [
            'title' => 'Home',
        ],
    ]);
})->name('home');
