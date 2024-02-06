<?php

namespace App\Libraries;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NavLibrary extends BaseLibrary
{
    static function pull_stations(): array|null
    {
        return Cache::remember('navlibrary.stations', 60 * 5, function () {
            $url = 'https://raw.githubusercontent.com/VATGER-Nav/datahub/main/data.json';
            $client = self::constructClient();
            try {
                $res = $client->get($url);
                return json_decode($res->getBody(), false, 512, JSON_THROW_ON_ERROR);
            } catch (GuzzleException | \JsonException $e) {
                Log::debug($e->getMessage());
                return null;
            }
        });
    }

    static function sync_stations(): void
    {
        $stations = self::pull_stations();
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
                $d->setAttribute('gcap_class_group', strval($s?->gcap_status ?? '0'));
                $d->setAttribute('gcap_training_airport', $s?->gcap_training_aiport ?? false);
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
}
