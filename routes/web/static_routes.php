<?php

function redir(string $to): Closure
{
    return function () use ($to) {
        return redirect($to);
    };
}

Route::prefix('redirect')->group(function () {
    Route::get('knowledgebase', redir('https://knowledgebase.vatsim-germany.org/'))->name('redirect.knowledgebase');
    Route::get('knowledgebase/contact', redir('https://knowledgebase.vatsim-germany.org/books/contact/page/contact-vatsim-germany'))->name(
        'redirect.knowledgebase.contact',
    );
    Route::get('knowledgebase/start', redir('https://knowledgebase.vatsim-germany.org/shelves/welcome-willkommen'))->name(
        'redirect.knowledgebase.start',
    );
    Route::get('knowledgebase/start-pilot', redir('https://knowledgebase.vatsim-germany.org/books/pilot'))->name(
        'redirect.knowledgebase.start-pilot',
    );
    Route::get('knowledgebase/start-atc', redir('https://knowledgebase.vatsim-germany.org/books/atc'))->name('redirect.knowledgebase.start-atc');
    Route::get('knowledgebase/training-pilot', redir('https://knowledgebase.vatsim-germany.org/books/ausbildungsubersicht-ptd'))->name(
        'redirect.knowledgebase.training-pilot',
    );

    Route::get('sectorfiles', redir('https://files.aero-nav.com/edxx'))->name('redirect.sectorfiles');

    Route::get('board', redir('https://board.vatsim-germany.org/'))->name('redirect.board');

    Route::get('discord', redir('https://community.vatsim.net/'))->name('redirect.discord');

    //Route::get('support', redir('https://support.vatsim-germany.org/'))->name('redirect.support');

    Route::get('support/feedback', redir('https://support.vatsim-germany.org/open.php?topicId=19'))->name('redirect.support.feedback');

    Route::get('spreadshop', redir('https://vatsim-germany.myspreadshop.de/'))->name('redirect.spreadshop');

    Route::get('ts3', redir('ts3server://ts3.vatsim-germany.org'))->name('redirect.ts3');

    Route::get('training-center', redir('https://knowledgebase.vatsim-germany.org/books/atc'))->name('redirect.training-center');

    Route::get('vatger-tours', redir('https://tours.vatsim-germany.org'))->name('redirect.vatger-tours');
});
