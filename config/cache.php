<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use VatsimData\DatafeedClasses\AtcRating;
use VatsimData\DatafeedClasses\Atis;
use VatsimData\DatafeedClasses\Controller;
use VatsimData\DatafeedClasses\ControllerWithTransceivers;
use VatsimData\DatafeedClasses\Facility;
use VatsimData\DatafeedClasses\FlightPlan;
use VatsimData\DatafeedClasses\General;
use VatsimData\DatafeedClasses\MilitaryRating;
use VatsimData\DatafeedClasses\Pilot;
use VatsimData\DatafeedClasses\PilotRating;
use VatsimData\DatafeedClasses\Prefile;
use VatsimData\DatafeedClasses\Rating;
use VatsimData\DatafeedClasses\RootObject;
use VatsimData\DatafeedClasses\Server;
use VatsimData\StatusClasses\Data;
use VatsimData\TransceiverClasses\Transceiver;
use VatsimData\TransceiverClasses\TransceiverOwner;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),

    // VATSIM feed objects are intentionally cached and must remain serializable.
    'serializable_classes' => [
        Carbon::class,
        CarbonImmutable::class,
        stdClass::class,
        AtcRating::class,
        Atis::class,
        Controller::class,
        ControllerWithTransceivers::class,
        Facility::class,
        FlightPlan::class,
        General::class,
        MilitaryRating::class,
        Pilot::class,
        PilotRating::class,
        Prefile::class,
        Rating::class,
        RootObject::class,
        Server::class,
        Data::class,
        VatsimData\StatusClasses\RootObject::class,
        VatsimData\TransceiverClasses\RootObject::class,
        Transceiver::class,
        TransceiverOwner::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |         "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [
        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'lock_connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [env('MEMCACHED_USERNAME'), env('MEMCACHED_PASSWORD')],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),
];
