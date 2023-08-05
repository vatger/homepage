<?php

namespace App\Libraries\VATSIM;

use App\Models\Navigation\Aerodrome;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Stevebauman\Purify\Facades\Purify;

class EventLibrary
{
    /**
     * Get a single event from the VATSIM API
     *
     * @param int $eventId
     * @return mixed
     */
    public static function getEvent(int $eventId = null)
    {
        if ($eventId != null) {
            // https://my.vatsim.net/api/v2/events/view/4223
            return Cache::remember('de.vatsim-germany.events.single.' . $eventId, 600, function () use ($eventId) {
                $response = Http::get('https://my.vatsim.net/api/v2/events/view/' . $eventId);
                return json_decode($response)->data;
            });
        }
        return false;
    }

    /**
     * Queries the myVatsim event API and selects only german events.
     * API Response is in ascending date, so order does not need to be checked.
     * Parsed response ($count events) data cached for 10 minutes (600s)
     *
     * @param int $count
     * @return string
     */
    public static function getEvents(int $count = 6, bool $nocache = false)
    {
        if ($nocache) {
            return self::loadEventData($count);
        } else {
            // Return event date, either cached (10 minutes), or by executing the function
            return Cache::remember('de.vatsim-germany.events.next', 60 * 10, function () use ($count) {
                return self::loadEventData($count);
            });
        }
    }

    public static function getAerodromeEvent(string $icao)
    {
        if (strlen($icao) != 4) {
            abort(404, 'Unknown icao code ' . $icao);
        }

        return self::getAerodromeEvents($icao, 1);
    }

    public static function getAerodromeEvents(string $icao, int $count = 6)
    {
        if (strlen($icao) != 4) {
            abort(404, 'Unknown icao code ' . $icao);
        }

        return Cache::remember('de.vatsim-germany.events.aerodrome.' . $icao, 600, function () use ($icao, $count) {
            $events = self::loadEvents();

            $eventArray = [];

            $index = 0;

            foreach ($events as $e) {
                if ($index >= $count) {
                    break;
                }

                foreach ($e->airports as $a) {
                    if ($a->icao == $icao) {
                        $nextEvent = $e;
                        if ($nextEvent != null) {
                            // Prevent xss attack
                            $nextEvent->description = Purify::clean($nextEvent->description);
                        }
                        $eventArray[] = $nextEvent;
                        $index++;
                    }
                }
            }

            return json_encode($eventArray);
        });
    }

    /**
     * Load and cache all upcoming events from VATSIM API.
     *
     * @return array
     */
    private static function loadEvents(): array
    {
        return Cache::remember('de.vatsim-germany.events.all', 600, function () {
            $response = Http::get('https://my.vatsim.net/api/v1/events/all');
            return json_decode($response)->data;
        });
    }

    /**
     * @param int $count
     * @return false|string
     */
    public static function loadEventData(int $count): string|false
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

        // Return json encoded data (i.e. the <= 6 found events)
        return json_encode($eventArray);
    }
}
