<?php

namespace App\Libraries\VATSIM;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * DataFeedLibrary
 *
 * This class does download and cache the VATSIM.net datafeed
 */
class DataFeedLibrary
{
    /**
     * The base URL to use to fetch the current VATSIM.net status file from
     */
    protected static $_baseStatusUrl = 'https://status.vatsim.net/status.json';

    /**
     * PREG patterns for german atc stations
     */
    protected static $deAtcPattern = '/(ED[A-Z]{2}|ET[AHIMNS]{1}[A-Z]{1})/A';

    /**
     * Download and cache the status file
     *
     * @return string The json representation of the status file
     */
    private static function _downloadStatusFile()
    {
        return Cache::remember('net.vatsim.status', 24 * 60 * 60, function () {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::$_baseStatusUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        });
    }

    /**
     * Update the datafeed in our local cache
     *
     * The feed will be cached for 59 seconds to allow / force updates via supervisor / artisan commands later
     *
     * @return string The json representation of the datafeed
     */
    public static function UpdateDataFeed()
    {
        return Cache::remember('net.vatsim.datafeed', 59, function () {
            $status = json_decode(self::_downloadStatusFile());
            if ($status == null || $status == false) {
                Cache::forget('net.vatsim.status');
                return false;
            }
            $availableUrls = $status->data->v3;
            $url = $availableUrls[rand(0, sizeof($availableUrls) - 1)];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        });
    }

    /**
     * Try to retrieve the metar of a given icao code
     *
     * @param $icao string The 4 letter icao code of an aerodrome
     * @return false|string False if the length of $icao is not exactly 4 | String of the metar in other cases
     */
    public static function Metar(string $icao): false|string
    {
        // In any case where the icao option is not exactly 4 characters in length skip
        if (strlen($icao) !== 4) {
            return false;
        }

        return Cache::remember('net.vatsim.metar.' . $icao, 15 * 60, function () use ($icao) {
            $status = json_decode(self::_downloadStatusFile());
            if (!$status) {
                Cache::forget('net.vatsim.status');
                return false;
            }
            $availableUrls = $status->metar;
            $url = $availableUrls[rand(0, sizeof($availableUrls) - 1)] . '?id=' . $icao;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        });
    }

    public static function Pilots(): array
    {
        $df = self::UpdateDataFeed();
        if ($df) {
            return json_decode($df)->pilots;
        } else {
            return [];
        }
    }

    public static function PilotsArrivingAerodrome(string $icao): array
    {
        $pilots = self::Pilots();

        $results = [];
        foreach ($pilots as $p) {
            if ($p->flight_plan != null && $p->flight_plan->arrival == $icao) {
                $results[] = $p;
            }
        }
        return $results;
    }

    public static function Controllers(): array
    {
        $df = self::UpdateDataFeed();
        if ($df) {
            return json_decode($df)->controllers;
        } else {
            return [];
        }
    }

    public static function ControllersLocal(): array
    {
        $resultList = [];
        $atcs = self::Controllers();
        foreach ($atcs as $a) {
            if (Str::contains($a->callsign, 'OBS')) {
                continue;
            }
            if (preg_match(self::$deAtcPattern, $a->callsign) == 1) {
                $resultList[] = $a;
            }
        }
        return $resultList;
    }

    public static function Atises(): array
    {
        $df = self::UpdateDataFeed();
        if ($df) {
            return json_decode($df)->atis;
        } else {
            return [];
        }
    }
}
