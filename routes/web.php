<?php

use App\Http\Controllers\Administration\Content\MediaController;
use App\Http\Controllers\Administration\Content\PartnerController;
use App\Http\Controllers\Administration\Content\ShortLinkController;
use App\Models\Partner;
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

####################
# Main Website     #
####################
Route::domain(parse_url(config('app.url'), PHP_URL_HOST))->group(function () {
    ##############
    # SHORT LINK #
    ##############
    Route::get('/r/{shortLink}', [ShortLinkController::class, 'viewLink']);

    ##################
    # AUTHENTICATION #
    ##################
    require_once 'web/authentication.php';

    ##########
    # PILOTS #
    ##########
    require_once 'web/pilots.php';

    ###############
    # CONTROLLERS #
    ###############
    require_once 'web/controllers.php';

    ##############
    # MEMBERSHIP #
    ##############
    require_once 'web/membership.php';

    ##############
    # EVENTS     #
    ##############
    require_once 'web/events.php';

    ###################
    # GETTING STARTED #
    ###################
    require_once 'web/getting-started.php';

    ########
    # HELP #
    ########
    require_once 'web/help.php';

    ##################
    # ADMINISTRATION #
    ##################
    require_once 'web/admin.php';

    ##################
    # SPECIAL ROUTES #
    ##################

    require_once 'web/euroscope.php';

    Route::get('resources/media/{mediaFilePath}', [MediaController::class, 'showPublic']);

    Route::get('/gdpr', function () {
        return view('homepage.general.extra.gdpr');
    })->name('gdpr');

    ###################
    # CHANGE LANGUAGE #
    ###################
    Route::get('language/{lang?}', function ($lang = 'de') {
        Session::put('language', $lang);
        return redirect()
            ->back()
            ->withInput();
    })->name('language.change');

    ############################
    # LANDING & COVER ALL PAGE #
    ############################
    Route::get('/', function () {
        $partners = Partner::all();

        return view('pages.landing.index')->with(['partners' => $partners]);
    })->name('landing');

    ############
    # API DOKU #
    ############
    Route::get('documentation', function () {
        return view('homepage.general.extra.apidoku');
    });

    Route::get('/partners', [PartnerController::class, 'viewAll'])->name('partners');
});
