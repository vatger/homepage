<?php

namespace App\Libraries\Authorization;

use App\Models\Navigation\Chart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Keys\AsymmetricSecretKey;
use ParagonIE\Paseto\Protocol\Version2;

class ChartAuthorization
{
    public static function grantAccessToken(Chart|int $chart)
    {
        if (is_numeric($chart)) {
            $chart = Chart::find($chart);
        }

        if ($chart->fri == 'vfr') {
            // Generate time limited access token
            $encodedSecretKey = config('paseto.paseto_key');
            if (!empty($encodedSecretKey)) {
                $sk = AsymmetricSecretKey::fromEncodedString($encodedSecretKey);
                $token = Builder::getPublic($sk, new Version2())
                    ->setExpiration(Carbon::now()->addSeconds(90))
                    ->setIssuer('vatsim-germany.org')
                    ->setAudience('nav.vatsim-germany.org')
                    ->setSubject('VATSIM Germany chart download authorization')
                    ->setIssuedAt(Carbon::now())
                    ->setNotBefore(Carbon::now())
                    ->set('files', [basename($chart->href)]);
                return $token;
            }
        }
        return false;
    }
}
