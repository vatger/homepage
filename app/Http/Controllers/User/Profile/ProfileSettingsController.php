<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Ajax\Response;
use App\Http\Controllers\Controller;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function abort;

class ProfileSettingsController extends Controller
{
    /**
     * Save user appearance settings
     *
     * @return mixed|void|Response
     */
    public function submitAppearance(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        $darkMode = false;
        if ($request->get('dark-mode-select') == 'true') {
            $darkMode = true;
        }

        $set = Auth::user()
            ->settings()
            ->update([
                'dark_mode' => $darkMode,
                'color' => $request->get('color-select'),
            ]);

        if ($set == 0) {
            abort(500, 'An error has occurred');
        }
    }

    public function submitLanguage(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        $set = Auth::user()
            ->settings()
            ->update([
                'language' => $request->get('lang'),
            ]);

        if ($set == 0) {
            abort(500, 'An error has occurred');
        }

        \Session::put('language', $request->get('lang'));
    }

    //TODO: Complete
    public function createTeamspeakRegistration(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        return true;
        //$reg = TeamSpeakWebQuery::registerViaUid(Auth::user(), '0.0.0.0', $request->post('uid'));

        //if (!$reg) abort(500, "An error has occurred");
    }
}
