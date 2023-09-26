<?php

namespace App\Libraries;

use App\Models\Navigation\Aerodrome;
use CobaltGrid\VatsimStandStatus\StandStatus;
use Illuminate\Support\Facades\File;

class StandStatusLibrary
{
    private static float $maxStandDistance = 0.03; // In kilometres
    private static bool $hideStandSidesWhenOccupied = true;
    private static int $maxDistanceFromAirport = 5; // In kilometres
    private static int $maxAircraftHeight = 300; // In feet above the aerodrome
    private static int $maxAircraftGroundspeed = 10; // In knots
    private static array $standExtensions = ['R', 'L', 'A', 'B', 'C'];

    public static function status(Aerodrome $aerodrome): array
    {
        try {
            $standFilePath = storage_path('app') . '/navigation/stands/' . strtolower($aerodrome->icao) . '.csv';

            if (File::exists($standFilePath)) {
                $stands = new StandStatus($aerodrome->latitude, $aerodrome->longitude);

                $stands->setMaxStandDistance(self::$maxStandDistance);
                $stands->setHideStandSidesWhenOccupied(self::$hideStandSidesWhenOccupied);
                $stands->setMaxDistanceFromAirport(self::$maxDistanceFromAirport);
                $stands->setMaxAircraftAltitude($aerodrome->elevation + self::$maxAircraftHeight);
                $stands->setMaxAircraftGroundspeed(self::$maxAircraftGroundspeed);
                $stands->setStandExtensions(self::$standExtensions);

                $stands->loadStandDataFromCSV($standFilePath)->parseData();

                return collect($stands->stands())
                    ->map(function ($stand) {
                        return [
                            'id' => $stand->getName(),
                            'latitude' => floatval($stand->latitude),
                            'longitude' => floatval($stand->longitude),
                            'occupier' => $stand->occupier?->callsign,
                        ];
                    })
                    ->toArray();
            }
        } catch (\Exception $e) {
        }
        return [];
    }
}
