<?php

namespace App\Libraries;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NavLibrary extends BaseGithubLibrary
{
    static function sync_stations(): void
    {
        $repo = 'VATGER-Nav/datahub';
        $branch = 'main';
        $path = 'data.json';

        $stations = self::github_dl_file($repo, $branch, $path);
        if (empty($stations)) {
            return;
        }
        Station::query()->update(['active' => false]);
        foreach ($stations as $s) {
            try {
                if (!isset($s->logon) || !isset($s->description)) {
                    continue;
                }

                // create the station
                $d = Station::where('ident', 'LIKE', $s->logon)->firstOrNew();
                $d->setAttribute('active', true);
                $d->setAttribute('ident', $s->logon);
                $d->setAttribute('frequency', floatval($s?->frequency ?? 199.998));
                $d->setAttribute('name', $s->description);
                $gcap_class = $s?->gcap_status ?? '0';
                $gcap_class = $gcap_class == '0' || $gcap_class == '1' ? intval($gcap_class) : 2;
                $d->setAttribute('gcap_class', $gcap_class);
                $d->setAttribute('gcap_class_group', strval($s->gcap_status ?? '0'));
                $d->setAttribute('gcap_training_airport', $s->gcap_training_airport ?? false);
                $d->setAttribute('s1_twr', $s->s1_twr ?? false);
                $d->save();

                // attach to aerodromes
                $aerodromes = [];
                $test_icao = Str::substr($s->logon, 0, 4);
                if (Aerodrome::where('icao', 'LIKE', $test_icao)->exists()) {
                    $aerodromes[] = $test_icao;
                }
                if (isset($s->relevant_airports)) {
                    $aerodromes = array_merge($aerodromes, $s->relevant_airports);
                }
                foreach ($d->aerodromes as $aerodrome) {
                    $d->aerodromes()->detach($aerodrome->id);
                }
                foreach ($aerodromes as $a) {
                    $aerodrome = Aerodrome::where('icao', 'LIKE', $a)->first();
                    if (!$aerodrome) {
                        continue;
                    }
                    $d->aerodromes()->attach($aerodrome->id);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public static function sync_stands(): void
    {
        $client = self::constructClient();

        $repo = 'VATGER-Nav/airport-data';
        $branch = 'production';
        $path = 'api';

        $files = self::github_get_file_list($repo, $branch, $path);

        foreach ($files as $file) {
            if (!str_ends_with($file->name, ".csv"))
                continue;
            $content = $client->get($file->download_url)->getBody()->getContents();
            $filename = Str::lower($file->name);
            $filePath = "navigation/stands/$filename";
            Storage::put($filePath, $content);

        }
    }

    public static function download_airport_data(string $icao): ?object
    {
        $repo = 'VATGER-Nav/airport-data';
        $branch = 'production';
        $path = 'api/airports.json';

        $airports = Cache::remember('airports-data', 60 * 60 * 4, function () use ($repo, $branch, $path) {
            return self::github_dl_file($repo, $branch, $path)?->airports;
        });

        if (empty($airports)) return null;

        foreach ($airports as $airport) {
            if ($airport?->icao && strtolower($airport?->icao) == strtolower($icao)) return $airport;
        }
        return null;
    }
}
