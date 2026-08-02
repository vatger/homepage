<?php

namespace App\Libraries\VATSIM;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use VatsimData\Datafeed;
use VatsimData\DatafeedClasses\Controller;
use VatsimData\DatafeedClasses\ControllerWithTransceivers;

/**
 * DataFeedLibrary
 */
class DataFeedLibrary
{
    /**
     * Build the live ATC and traffic summary shown on aerodrome list cards.
     *
     * @param  iterable<Aerodrome>  $aerodromes
     * @return array<int, array{roles: array<string, bool>, departures: int, arrivals: int}>
     */
    public static function AerodromeSummaries(iterable $aerodromes): array
    {
        $aerodromes = collect($aerodromes);
        $summariesByIcao = Datafeed::AerodromeSummaries($aerodromes->pluck('icao'));
        $summaries = [];

        foreach ($aerodromes as $aerodrome) {
            $summary = $summariesByIcao[strtoupper($aerodrome->icao)];
            $summaries[$aerodrome->id] = [
                'roles' => $summary->roles,
                'departures' => $summary->departures,
                'arrivals' => $summary->arrivals,
            ];
        }

        return $summaries;
    }

    public static function ControllersAerodrome(Aerodrome $aerodrome): array
    {
        $matched_controllers = [];

        foreach ($aerodrome->stations as $station) {
            $match = Datafeed::ControllerForStation($station->ident, $station->frequency);
            if (! $match) {
                continue;
            }

            $controller = new ControllerWithTransceivers($match->controller);
            $controller->station = $station;
            $matched_controllers[] = $controller;
        }

        return $matched_controllers;
    }

    public static function Controller(Station $station): ?Controller
    {
        return Datafeed::ControllerForStation($station->ident, $station->frequency)?->controller;
    }
}
