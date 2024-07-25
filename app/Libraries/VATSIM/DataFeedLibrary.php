<?php

namespace App\Libraries\VATSIM;

use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * DataFeedLibrary
 *
 * This class does download and cache the VATSIM.net datafeed
 *
 * @deprecated
 */
class DataFeedLibrary
{
    /**
     * The base URL to use to fetch the current VATSIM.net status file from
     */
    protected static string $_baseStatusUrl = 'https://status.vatsim.net/status.json';


    protected static string $_cachedDfUrl = 'http://docker.vatsim-germany.org:8007/datafeed';
    protected static string $_uncachedDfUrl = 'https://data.vatsim.net/v3/vatsim-data.json';

    /**
     * PREG patterns for german atc stations
     */
    protected static string $deAtcPattern = '/(ED[A-Z]{2}|ET[AHIMNS]{1}[A-Z]{1})/A';

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
     * @return ?object The json representation of the datafeed
     */
    private static function UpdateDataFeed(): ?object
    {

        $use_df_cache = env('VATSIM_DATAFEED_USE_CACHE', true);
        $cacheUrl = $use_df_cache ? self::$_cachedDfUrl : self::$_uncachedDfUrl;

        return Cache::remember('datafeed.datafeed', 59, function () use ($use_df_cache, $cacheUrl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $cacheUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $data = curl_exec($ch);
            curl_close($ch);
            if ($use_df_cache)
                return json_decode($data)?->data;
            else
                return json_decode($data);
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
            return $df->pilots;
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
            return $df->controllers;
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

    public static function ControllersAerodrome(Aerodrome $aerodrome): array
    {
        $all_controllers = self::ControllersLocal();

        $matched_controllers = [];
        //dd($all_controllers);
        //dd($aerodrome_stations->toArray());
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

    public static function Atises(): array
    {
        $df = self::UpdateDataFeed();
        if ($df) {
            return $df->atis;
        } else {
            return [];
        }
    }

    public static function AtisAerodrome(Aerodrome $aerodrome): ?object
    {
        $all_atises = self::Atises();
        foreach ($all_atises as $atis) {
            if (Str::substr($atis?->callsign, 0, 4) == $aerodrome->icao) {
                return $atis;
            }
        }
        return null;
    }
}
