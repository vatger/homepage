<?php

namespace App\Http\Controllers;

use App\Models\Membership\User\User;
use Laravel\Passport\Passport;

class OpenIdConnectController
{
    public function userinfo()
    {
        $token = Passport::token();
        $user = User::findOrFail($token->user()->first()->id);
        $userinfo = [];

        $userinfo['id'] = $user->id;
        if ($token->can('name')) {
            $userinfo['firstname'] = $user->firstname;
            $userinfo['lastname'] = $user->lastname;
        }
        if ($token->can('email')) {
            $userinfo['email'] = $user->email;
        }
        if ($token->can('rating')) {
            $userinfo['rating_atc'] = $user->vatsimDetails->rating_atc;
            $userinfo['rating_atc_short'] = $user->vatsimDetails->rating_atc_short;
            $userinfo['rating_pilot'] = $user->vatsimDetails->rating_pilot;
            $userinfo['rating_pilot_short'] = $user->vatsimDetails->rating_pilot_short;
            $userinfo['rating_military'] = $user->vatsimDetails->rating_military;
            $userinfo['rating_military_short'] = $user->vatsimDetails->rating_military_short;
        }
        if ($token->can('assignment')) {
            $userinfo['region_code'] = $user->vatsimDetails->region_code;
            $userinfo['division_code'] = $user->vatsimDetails->division_code;
            $userinfo['subdivision_code'] = $user->vatsimDetails->subdivision_code;
            $userinfo['fir_code'] = $user->fir?->slug;
        }

        $userinfo['openid'] = 'vatger v' . app()->version();
        return response()->json((object)$userinfo);
    }

}
