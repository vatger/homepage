<?php

namespace App\Libraries\VATSIM;

use App\Libraries\BaseLibrary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Stevebauman\Purify\Facades\Purify;

class EventLibrary extends BaseLibrary
{
    private static ImageManager $_imageManager;

    /**
     * Get a single event from the VATSIM API
     * https://my.vatsim.net/api/v2/events/view/<event_id>
     */
    public static function getEvent(int $id): mixed
    {
        return Cache::remember('de.vatsim-germany.events.view.' . $id, 60 * 10, function () use ($id) {
            $res = Http::get('https://my.vatsim.net/api/v2/events/view/' . $id);
            return json_decode($res->body())?->data;
        });
    }

    /**
     * Queries the myVatsim event API and selects only german events.
     * API Response is in ascending date, so order does not need to be checked.
     * Parsed response ($count events) data cached for 10 minutes (600s)
     *
     * @param int $count
     * @param bool $nocache
     * @return false|string
     */
    public static function getEvents(int $count = 6, bool $nocache = false): false|string
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

        return Cache::remember('de.vatsim-germany.events.aerodrome.' . $icao, \DateInterval::createFromDateString("3 hours"), function () use ($icao, $count) {
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
                        if ($nextEvent != null) {
                            // Prevent xss attack
                            $nextEvent->description = Purify::clean($nextEvent->description);
                        }
                        $eventArray[] = $nextEvent;
                        $index++;
                    }
                }
            }

            return $eventArray;
        });
    }

    /**
     * Load and cache all upcoming events from VATSIM API.
     */
    private static function loadEvents(): array
    {
        return Cache::remember('de.vatsim-germany.events.all', 60 * 10, function () {
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

        $client = self::constructClient([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', // Spoof the User-Agent Header
                'Referer' => config('app.url'),
                'Host' => parse_url(config('app.url'), PHP_URL_HOST),
            ]
        ]);

        self::$_imageManager = new ImageManager(new Driver());

        // Loop through response array (i.e. through events)
        foreach ($events as $event) {
            foreach ($event->airports as $event_airport) {
                $icao = substr($event_airport->icao, 0, 2);
                if (strtolower($icao) == 'ed' || strtolower($icao) == 'et') {
                    $event->banner = self::_downloadImage($client, $event);

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

    private static function _downloadImage(\GuzzleHttp\Client $client, $event): string
    {
        $filepath = "public/banners/" . $event->id . ".webp";

        if (!Storage::exists($filepath)) {
            $response = $client->get($event->banner);
            if ($response->getStatusCode() != 200) {
                return $event->banner;
            }

            try {
                $data = $response->getBody()->getContents();

                $image = self::$_imageManager->read($data);

                $image->scale(width: 1920);

                Storage::put($filepath, $image->toWebp()->toString());
            } catch (\Exception $e) {
                Log::warning($e->getMessage());
                return $event->banner;
            }
        }

        return Storage::url($filepath);
    }
}
