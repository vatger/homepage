<?php

use App\Http\Controllers\Administration\Content\MediaController;
use App\Http\Controllers\Administration\Content\ShortLinkController;
use App\Http\Controllers\OpenIdConnectController;
use App\Livewire\SupportPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ###################
// Main Website     #
// ###################

// #############
// SHORT LINK #
// #############
Route::get('/r/{shortLink}', [ShortLinkController::class, 'viewLink']);

// #################
// AUTHENTICATION #
// #################
require_once 'web/authentication.php';

// #########
// PILOTS #
// #########
require_once 'web/pilots.php';

// ##############
// CONTROLLERS #
// ##############
require_once 'web/controllers.php';

// #############
// MEMBERSHIP #
// #############
require_once 'web/membership.php';

// #############
// EVENTS     #
// #############
require_once 'web/events.php';

// #################
// BOOKING IMAGES #
// #################
require_once 'web/booking_images.php';

// ##################
// GETTING STARTED #
// ##################
require_once 'web/getting-started.php';

// ##################
// LEGAL STUFF     #
// ##################
require_once 'web/legal.php';

// #################
// ADMINISTRATION #
// #################
require_once 'web/admin.php';

// #################
// SPECIAL ROUTES #
// #################
require_once 'web/static_routes.php';

// #################
// OIDC ROUTES    #
// #################
Route::get('/oauth/userinfo', [OpenIdConnectController::class, 'userinfo'])->middleware('auth:openid_api')->name('openid.userinfo');

// #################
// MEDIA ROUTES   #
// #################
Route::get('resources/media/{mediaFilePath}', [MediaController::class, 'showPublic']);

// ##################
// CHANGE LANGUAGE #
// ##################
Route::get('language/{lang?}', function ($lang = 'de') {
    Session::put('language', $lang);
    if (Auth::check()) {
        $settings = Auth::user()->settings;
        $settings->language = $lang;
        $settings->save();
    }

    return redirect()
        ->back()
        ->withInput();
})->name('language.change');

// ###########################
// LANDING & COVER ALL PAGE #
// ###########################

Route::group([
    'middleware' => ['cookie.consent'],
    'excluded_middleware' => ['check-terms'],
], function () {
    Route::get(
        '/', function () {
            return view('pages.landing');
        })->name('landing');
});

Route::get('support', SupportPage::class)->name('redirect.support');
