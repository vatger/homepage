<?php

return [
    'username' => env('VIKUNJA_USER', ''),
    'password' => env('VIKUNJA_PWD', ''),
    'url' => rtrim(env('VIKUNJA_URL', 'http://vikunja.vatsim-germany.org:8013/api/v1'), '/'),
];
