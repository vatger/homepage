<?php

namespace App\Libraries;

use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Facades\File;
use VatsimData\Datafeed;
use VatsimData\StandStatus;

class StandStatusLibrary
{
    private static float $maxStandDistance = 0.03; // In kilometres

    private static bool $hideStandSidesWhenOccupied = true;

    private static int $maxDistanceFromAirport = 5; // In kilometres

    private static int $maxAircraftHeight = 300; // In feet above the aerodrome

    private static int $maxAircraftGroundspeed = 25; // In knots

    private static array $standExtensions = ['R', 'L', 'A', 'B', 'C'];

    public static function status(Aerodrome $aerodrome): ?StandStatus
    {
        try {
            $standFilePath = storage_path('app/navigation/stands/').strtolower($aerodrome->icao).'.csv';
            if (File::exists($standFilePath)) {
                $stands = new StandStatus(
                    $aerodrome->latitude,
                    $aerodrome->longitude,
                    StandStatus::COORD_FORMAT_DECIMAL,
                    $aerodrome->icao,
                );

                $stands->setMaxStandDistance(self::$maxStandDistance);
                $stands->setHideStandSidesWhenOccupied(self::$hideStandSidesWhenOccupied);
                $stands->setMaxDistanceFromAirport(self::$maxDistanceFromAirport);
                $stands->setMaxAircraftAltitude($aerodrome->elevation + self::$maxAircraftHeight);
                $stands->setMaxAircraftGroundspeed(self::$maxAircraftGroundspeed);
                $stands->setStandExtensions(self::$standExtensions);

                $stands->loadStandDataFromCSV($standFilePath)->parseData();

                return $stands;
            }
        } catch (\Exception $e) {
        }

        return null;
    }

    public static function standstatus(Aerodrome $aerodrome): array
    {
        $status = self::status($aerodrome);
        if (! $status) {
            return [];
        }

        return collect($status->stands())
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

    public static function aircraftstatus(Aerodrome $aerodrome): array
    {
        $status = self::status($aerodrome);
        if (! $status) {
            return [];
        }

        $flightStatuses = $status->flightStatuses();
        $pilotTracks = Datafeed::PilotTracks();
        $standIds = collect($status->allStands())
            ->mapWithKeys(fn ($stand) => [$stand->getName() => true])
            ->all();

        return collect($status->allAircraft())
            ->map(function ($aircraft) use ($flightStatuses, $pilotTracks, $standIds) {
                $groundstate = $flightStatuses[$aircraft->callsign]?->value ?? 'unknown';
                $standIndex = $aircraft->getStandIndex();

                return [
                    'callsign' => $aircraft->callsign,
                    'type' => $aircraft->flight_plan?->aircraft_short,
                    'departure' => $aircraft->flight_plan?->departure,
                    'arrival' => $aircraft->flight_plan?->arrival,
                    'altitude' => intval($aircraft->altitude),
                    'groundspeed' => intval($aircraft->groundspeed),
                    'latitude' => floatval($aircraft->latitude),
                    'longitude' => floatval($aircraft->longitude),
                    'heading' => $aircraft->heading,
                    'groundstate' => $groundstate,
                    'gate' => str_contains($groundstate, 'gate') && $standIndex !== null && isset($standIds[$standIndex])
                        ? $standIndex
                        : null,
                    'track' => collect($pilotTracks[$aircraft->cid]?->points ?? [])
                        ->map(fn ($point) => [
                            'latitude' => $point->latitude,
                            'longitude' => $point->longitude,
                            'heading' => $point->heading,
                            'recorded_at' => $point->recorded_at,
                            'predicted' => $point->predicted,
                        ])
                        ->values()
                        ->toArray(),
                ];
            })
            ->values()
            ->toArray();
    }
}
