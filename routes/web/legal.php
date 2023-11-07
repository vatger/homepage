<?php

Route::get('/gdpr', function () {
    return view('pages.gdpr');
})->name('gdpr');

Route::get('/datenschutz', function () {
    return view('pages.gdpr');
});

Route::get('/imprint', function () {
    return view('pages.gdpr');
})->name('imprint');

Route::get('/impressum', function () {
    return view('pages.gdpr');
});
