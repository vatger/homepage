<?php

namespace App\Libraries\VATSIM;

use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use VatsimData\Datafeed;

/**
 * DataFeedLibrary
 *
 */
class DataFeedLibrary
{
    public static function ControllersAerodrome(Aerodrome $aerodrome): array
    {
        $all_controllers = Datafeed::ControllersLocal();

        $matched_controllers = [];

        foreach ($all_controllers as $controller) {
            $aerodrome_station = $aerodrome
                ->stations()
                ->where('ident', 'LIKE', Str::substr($controller?->callsign, 0, 4) . '%')
                ->where('frequency', '=', floatval($controller?->frequency))
                ->first();
            if ($aerodrome_station) {
                $controller->station = $aerodrome_station;
                $matched_controllers[] = $controller;
            }
        }
        return $matched_controllers;
    }
}
