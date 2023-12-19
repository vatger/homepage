<?php
return [
    'token' => env('MAILCOW_TOKEN', ''),
    'url' => rtrim(env('MAILCOW_URL', 'http://mail.vatsim-germany.org/api/v1'), '/'),
    'doku-url' => env('MAILCOW_DOKU_URL', 'https://board.vatsim-germany.org/threads/anleitung-fuer-die-e-mail-adressen.69866/'),
];
