<?php

namespace App\Libraries\StandStatus;

use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\File;

class StandStatusLibrary
{
    public static function status(Aerodrome $aerodrome): array
    {
        $standFilePath = storage_path('app') . '/navigation/stands/' . strtolower($aerodrome->icao) . '.csv';

        if (File::exists($standFilePath)) {
            $stands = new StandStatus($aerodrome->latitude, $aerodrome->longitude);
            $stands
                ->setMaxStandDistance(0.02)
                ->setMaxDistanceFromAirport(5)
                ->setMaxAircraftAltitude($aerodrome->elevation + 300);
            $stands->loadStandDataFromCSV($standFilePath)->parseData();

            if ($stands) {
                $result = [];
                foreach ($stands->stands() as $stand) {
                    $result[] = [
                        'id' => $stand->getName(),
                        'latitude' => floatval($stand->latitude),
                        'longitude' => floatval($stand->longitude),
                        'occupier' => $stand->occupier?->callsign,
                    ];
                }
                return $result;
            }
        }

        return [];
    }
}
