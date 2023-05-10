<?php
/*
|--------------------------------------------------------------------------
|  WEB API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "web_api" middleware group. Use only for website ajax requests.
| Other API requests shall be handled by api.php
|
*/
if (file_exists(base_path('modules/atciss/routes/atciss/api.php'))) {
    include base_path('modules/atciss/routes/atciss/api.php');
}

require_once 'web_api/api.php';
