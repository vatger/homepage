<?php
return [
    'token' => env('OSTICKET_TOKEN', ''),
    'token_official' => env('OSTICKET_TOKEN_OFFICIAL', ''),
    'url' => rtrim(env('OSTICKET_URL', 'http://support.vatsim-germany.org:8000/'), '/'),
    'url_official' => rtrim(env('OSTICKET_URL_OFFICIAL', 'http://support.vatsim-germany.org:/api/'), '/'),
];
