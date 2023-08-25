<?php

namespace App\Libraries\StandStatus;

use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\File;

class StandStatusLibrary
{
    public static function status(Aerodrome $aerodrome): array
    {
        try {
            $standFilePath = storage_path('app') . '/navigation/stands/' . strtolower($aerodrome->icao) . '.csv';

            if (File::exists($standFilePath)) {
                $stands = new StandStatus($aerodrome->latitude, $aerodrome->longitude);
                $stands->setMaxAircraftAltitude($aerodrome->elevation + 300);

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
