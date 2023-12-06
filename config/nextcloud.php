<?php
return [
    'username' => env('NEXTCLOUD_USER', ''),
    'password' => env('NEXTCLOUD_PWD', ''),
    'url' => rtrim(env('NEXTCLOUD_URL', 'https://dms.vatsim-germany.org/ocs/v1.php/cloud'), '/'),
];
