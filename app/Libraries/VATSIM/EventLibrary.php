<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use App\Libraries\ImageHelperLibrary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class EventLibrary extends BaseLibrary
{
    /**
     * Queries the myVatsim event API and selects only german events.
     * API Response is in ascending date, so order does not need to be checked.
     * Parsed response ($count events) data cached for 10 minutes (600s)
     */
    public static function getEvents(int $count = 6, bool $nocache = false): false|array
    {
        if ($nocache) {
            return self::loadEventData($count);
        } else {
            // Return event date, either cached (10 minutes), or by executing the function
            return Cache::remember('de.vatsim-germany.events.next', 0, function () use ($count) {
                return self::loadEventData($count);
            });
        }
    }

    public static function getAerodromeEvent(string $icao): ?object
    {
        $data = self::getAerodromeEvents($icao, 1);
        if (count($data)) {
            return null;
        }

        return $data[0];
    }

    public static function getAerodromeEvents(string $icao, int $count = 6): array
    {
        if (strlen($icao) != 4) {
            return [];
        }

        return Cache::remember('de.vatsim-germany.events.aerodrome.'.$icao, 0, function () use ($icao, $count) {
            $events = self::loadEvents();
            $eventArray = [];

            $index = 0;
            foreach ($events as $e) {
                if ($index >= $count) {
                    break;
                }

                foreach ($e->airports as $a) {
                    if (Str::upper($a->icao) == Str::upper($icao)) {
                        $nextEvent = $e;
                        $eventArray[] = $nextEvent;
                        $index++;
                    }
                }
            }

            return $eventArray;
        });
    }

    public static function loadEventData(int $count): array|false
    {
        $events = self::loadEvents();

        // Init array
        $eventArray = [];

        // Loop through response array (i.e. through events)
        foreach ($events as $event) {
            foreach ($event->airports as $event_airport) {
                $icao = substr($event_airport->icao, 0, 2);
                if (strtolower($icao) == 'ed' || strtolower($icao) == 'et') {
                    $eventArray[] = $event;
                    break;
                }
            }

            if (count($eventArray) == $count) {
                break;
            }
        }

        // Return data (i.e. the <= 6 found events)
        return $eventArray;
    }

    /**
     * Load and cache all upcoming events from VATSIM API.
     */
    private static function loadEvents(): array
    {
        return Cache::remember('de.vatsim-germany.events.all', 0, function () {
            $response = Http::get('https://my.vatsim.net/api/v1/events/all');
            $event_array = json_decode($response)->data;
            $image_lib = new ImageHelperLibrary;
            foreach ($event_array as $event) {
                $has_german_airport = collect($event->airports)
                    ->contains(
                        fn ($airport) => str_starts_with(strtoupper($airport->icao), 'ED')
                    );
                if ($has_german_airport) {
                    // clean the description to prevent XSS
                    $event->description = Purify::clean($event->description);
                    // cache and convert the banner for speed
                    $event->banner = $image_lib->get('event_banners/'.$event->id, $event->banner, fast: true);
                } else {
                    $event->description = '';
                    $event->banner = '';
                }

            }
            return $event_array;
        });
    }

    /**
     * Get a single event from the VATSIM API
     * https://my.vatsim.net/api/v2/events/view/<event_id>
     */
    public static function getEvent(int $id): ?object
    {
        $event = Cache::remember('de.vatsim-germany.events.view.'.$id, 60 * 10, function () use ($id) {
            $res = Http::get('https://my.vatsim.net/api/v2/events/view/'.$id);
            $res_data = json_decode($res->body());
            if (! $res_data || ! property_exists($res_data, 'data')) {
                return null;
            }

            return $res_data?->data;
        });
        if (! $event) {
            return null;
        }

        // clean the description to prevent XSS
        $event->description = Purify::clean($event->description);
        // cache and convert the banner for speed
        $image_lib = new ImageHelperLibrary;
        $event->banner = $image_lib->get('event_banners/'.$event->id, $event->banner);

        return $event;
    }
}
