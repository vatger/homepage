<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpenIdConnectController
{
    public function userinfo(Request $request)
    {
        $user = $request->user('openid_api');
        $user_client_id = $request->user()->token()->client->user_id;
        if ($user_client_id != null && $user_client_id != $user->id) {
            abort(401, 'This client can only used by ' . $user_client_id);
        }

        $userinfo = [];

        $userinfo['id'] = $user->id;
        if ($user->tokenCan('name')) {
            $userinfo['firstname'] = $user->firstname;
            $userinfo['lastname'] = $user->lastname;
            $userinfo['fullname'] = $user->firstname . ' ' . $user->lastname;
        }
        if ($user->tokenCan('email')) {
            $userinfo['email'] = $user->email;
        }
        if ($user->tokenCan('rating')) {
            $userinfo['rating_atc'] = $user->vatsimDetails->rating_atc;
            $userinfo['rating_atc_short'] = $user->vatsimDetails->rating_atc_short;
            $userinfo['rating_pilot'] = $user->vatsimDetails->rating_pilot;
            $userinfo['rating_pilot_short'] = $user->vatsimDetails->rating_pilot_short;
            $userinfo['rating_military'] = $user->vatsimDetails->rating_military;
            $userinfo['rating_military_short'] = $user->vatsimDetails->rating_military_short;
        }
        if ($user->tokenCan('assignment')) {
            $userinfo['region_code'] = $user->vatsimDetails->region_code;
            $userinfo['division_code'] = $user->vatsimDetails->division_code;
            $userinfo['subdivision_code'] = $user->vatsimDetails->subdivision_code;
            $userinfo['fir_code'] = $user->fir?->slug;
        }
        if ($user->tokenCan('teams')) {
            $teams = $user->teams();
            $userinfo['teams'] = collect($teams)->map(fn($team) => $team->name)->toArray();
        }

        if ($user->tokenCan('legacy')) {
            $userinfo['data']['cid'] = strval($user->id);
            if ($user->tokenCan('name')) {
                $userinfo['data']['personal']['name_first'] = $user->firstname;
                $userinfo['data']['personal']['name_last'] = $user->lastname;
                $userinfo['data']['personal']['name_full'] = $user->firstname . ' ' . $user->lastname;
                $userinfo['data']['personal']['country']['id'] = null;
                $userinfo['data']['personal']['country']['name'] = null;
            }
            if ($user->tokenCan('email')) {
                $userinfo['data']['personal']['email'] = $user->email;
            }
            if ($user->tokenCan('rating')) {
                $userinfo['data']['vatsim']['rating']['id'] = $user->vatsimDetails->rating_atc;
                $userinfo['data']['vatsim']['rating']['short'] = $user->vatsimDetails->rating_atc_short;
                $userinfo['data']['vatsim']['rating']['long'] = $user->vatsimDetails->rating_atc_long;
                $userinfo['data']['vatsim']['pilotrating']['id'] = $user->vatsimDetails->rating_pilot;
                $userinfo['data']['vatsim']['pilotrating']['short'] = $user->vatsimDetails->rating_pilot_short;
                $userinfo['data']['vatsim']['pilotrating']['long'] = $user->vatsimDetails->rating_pilot_long;
            }
            if ($user->tokenCan('assignment')) {
                $userinfo['data']['vatsim']['region']['id'] = $user->vatsimDetails->region_code;
                $userinfo['data']['vatsim']['region']['name'] = $user->vatsimDetails->region_name;
                $userinfo['data']['vatsim']['division']['id'] = $user->vatsimDetails->division_code;
                $userinfo['data']['vatsim']['division']['name'] = $user->vatsimDetails->division_name;
                $userinfo['data']['vatsim']['subdivision']['id'] = $user->vatsimDetails->subdivision_code;
                $userinfo['data']['vatsim']['subdivision']['name'] = $user->vatsimDetails->subdivision_name;
            }
            $userinfo['data']['oauth']['token_valid'] = 'true';
        }

        $userinfo['openid'] = 'vatger v' . config('app.version');
        return response()->json((object)$userinfo);
    }

}
