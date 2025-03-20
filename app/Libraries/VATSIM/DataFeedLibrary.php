<?php

namespace App\Libraries\VATSIM;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Support\Str;
use VatsimData\Datafeed;
use VatsimData\DatafeedClasses\ControllerWithTransceivers;

/**
 * DataFeedLibrary
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
                ->where('ident', 'LIKE', Str::substr($controller?->callsign, 0, 4).'%')
                ->where('frequency', '=', floatval($controller?->frequency))
                ->first();
            if ($aerodrome_station) {
                $controller = new ControllerWithTransceivers($controller);
                $controller->station = $aerodrome_station;
                $matched_controllers[] = $controller;

            }
        }

        return $matched_controllers;
    }

    public static function Controller(Station $station): ?object
    {
        $all_controllers = Datafeed::ControllersLocal();

        $callable = fn ($controller) => Str::substr($controller->callsign, 0, 4) == Str::substr($station->ident, 0, 4) &&
            Str::substr($controller->callsign, -3, 3) == Str::substr($station->ident, -3, 3) &&
            floatval($controller->frequency) == $station->frequency;

        return array_find($all_controllers, $callable);
    }
}
