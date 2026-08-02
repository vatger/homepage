<?php

namespace App\Services;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use VatsimData\Datafeed;

class LandingTrafficService
{
    private const CACHE_KEY = 'landing:live-traffic:v4';

    private const SVG_CACHE_KEY = 'landing:live-traffic-svg:v5';

    /**
     * @return array{aerodromes: array<int, array<string, mixed>>, stations: array<int, array<string, mixed>>, pilots: array<int, array<string, float|int|string>>, controllers: array<int, array<string, float|string>>}
     */
    public function snapshot(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinute(), function (): array {
            $aerodromes = Aerodrome::query()
                ->isDe()
                ->where('active', true)
                ->with('stations')
                ->get();

            $byIcao = $aerodromes->keyBy(fn (Aerodrome $aerodrome): string => Str::upper($aerodrome->icao));
            $traffic = $aerodromes->mapWithKeys(fn (Aerodrome $aerodrome): array => [
                $aerodrome->id => [
                    'id' => $aerodrome->id,
                    'icao' => Str::upper($aerodrome->icao),
                    'name' => $aerodrome->name,
                    'departures' => 0,
                    'arrivals' => 0,
                    'controllers' => 0,
                ],
            ])->all();

            $pilots = [];
            foreach (Datafeed::Pilots() as $pilot) {
                $flightPlan = $pilot->flight_plan;

                if ($flightPlan) {
                    $departure = $byIcao->get(Str::upper($flightPlan->departure));
                    $arrival = $byIcao->get(Str::upper($flightPlan->arrival));

                    if ($departure) {
                        $traffic[$departure->id]['departures']++;
                    }

                    if ($arrival) {
                        $traffic[$arrival->id]['arrivals']++;
                    }
                }

                if ($this->isWithinGermany($pilot->latitude, $pilot->longitude) && $this->isAirborne($pilot->altitude, $pilot->groundspeed)) {
                    $pilots[] = [
                        'callsign' => $pilot->callsign,
                        'latitude' => $pilot->latitude,
                        'longitude' => $pilot->longitude,
                        'heading' => $pilot->heading,
                        'altitude' => $pilot->altitude,
                        'groundspeed' => $pilot->groundspeed,
                    ];
                }
            }

            usort($pilots, fn (array $left, array $right): int => strcmp($left['callsign'], $right['callsign']));

            $stations = [];
            $controllers = [];
            foreach (Datafeed::ControllersLocal() as $controller) {
                $icao = Str::upper(Str::substr($controller->callsign, 0, 4));
                /** @var Aerodrome|null $aerodrome */
                $aerodrome = $byIcao->get($icao);

                if (! $aerodrome) {
                    continue;
                }

                /** @var Station|null $station */
                $station = $aerodrome->stations->first(fn (Station $station): bool => Str::startsWith(Str::upper($station->ident), $icao)
                    && floatval($station->frequency) === floatval($controller->frequency)
                );

                if (! $station) {
                    continue;
                }

                $traffic[$aerodrome->id]['controllers']++;
                $stations[] = [
                    'callsign' => $controller->callsign,
                    'frequency' => $controller->frequency,
                    'aerodrome' => $icao,
                    'name' => $station->name,
                ];

                if ($aerodrome->latitude !== null && $aerodrome->longitude !== null) {
                    $controllers[$icao] = [
                        'icao' => $icao,
                        'latitude' => (float) $aerodrome->latitude,
                        'longitude' => (float) $aerodrome->longitude,
                    ];
                }
            }

            $hotAerodromes = array_values(array_filter($traffic, fn (array $aerodrome): bool => $aerodrome['departures'] > 0 || $aerodrome['arrivals'] > 0 || $aerodrome['controllers'] > 0
            ));

            usort($hotAerodromes, fn (array $left, array $right): int => (($right['departures'] + $right['arrivals'] + ($right['controllers'] * 3)) <=> ($left['departures'] + $left['arrivals'] + ($left['controllers'] * 3)))
                ?: strcmp($left['icao'], $right['icao'])
            );
            usort($stations, fn (array $left, array $right): int => strcmp($left['callsign'], $right['callsign']));

            $atisCount = collect(Datafeed::Atis())
                ->filter(fn ($atis): bool => $byIcao->has(Str::upper(Str::substr($atis->callsign, 0, 4))))
                ->count();

            return [
                'aerodromes' => array_slice($hotAerodromes, 0, 7),
                'stations' => $stations,
                'pilots' => $pilots,
                'controllers' => array_values($controllers),
                'summary' => [
                    'departures' => array_sum(array_column($traffic, 'departures')),
                    'arrivals' => array_sum(array_column($traffic, 'arrivals')),
                    'controllers' => count($stations),
                    'atis' => $atisCount,
                ],
            ];
        });
    }

    public function svg(): string
    {
        return Cache::remember(self::SVG_CACHE_KEY, now()->addMinute(), function (): string {
            $snapshot = $this->snapshot();
            $width = 500;
            $height = 560;
            // Germany is taller than it is wide. Keep that real-world proportion
            // instead of stretching the country over the complete map canvas.
            $project = fn (float $latitude, float $longitude): array => [
                60 + (($longitude - 5.4) / (15.6 - 5.4) * 380),
                34 + ((55.2 - $latitude) / (55.2 - 47.0) * 492),
            ];

            $outlinePath = $this->germanyOutlinePath($project);
            $aircraftAssets = [
                'plane-fly-slow.svg' => [
                    'id' => 'plane-fly-slow',
                    'width' => 19.213709,
                    'markup' => $this->aircraftAssetMarkup('plane-fly-slow.svg'),
                ],
                'plane-fly-medium.svg' => [
                    'id' => 'plane-fly-medium',
                    'width' => 27.732616,
                    'markup' => $this->aircraftAssetMarkup('plane-fly-medium.svg'),
                ],
                'plane-fly-fast.svg' => [
                    'id' => 'plane-fly-fast',
                    'width' => 50.444382,
                    'markup' => $this->aircraftAssetMarkup('plane-fly-fast.svg'),
                ],
            ];
            $aircraftSymbols = collect($aircraftAssets)
                ->map(fn (array $aircraft): string => sprintf(
                    '<symbol id="%s" viewBox="0 0 %.6F 12.328641">%s</symbol>',
                    $aircraft['id'],
                    $aircraft['width'],
                    $aircraft['markup'],
                ))
                ->implode('');

            $pilotMarkers = collect($snapshot['pilots'])->map(function (array $pilot) use ($project, $aircraftAssets): string {
                [$x, $y] = $project((float) $pilot['latitude'], (float) $pilot['longitude']);
                $heading = (int) $pilot['heading'];
                $callsign = e((string) $pilot['callsign']);

                $speed = (int) $pilot['groundspeed'];
                $asset = $speed < 180
                    ? 'plane-fly-slow.svg'
                    : ($speed < 350 ? 'plane-fly-medium.svg' : 'plane-fly-fast.svg');
                $aircraft = $aircraftAssets[$asset];

                return sprintf('<g transform="translate(%.1f %.1f) rotate(%d)"><title>%s</title><use href="#%s" x="-20" y="-4.4" width="40" height="8.7" preserveAspectRatio="none"/></g>', $x,
                    $y, $heading - 90, $callsign, $aircraft['id']);

                return sprintf('<g transform="translate(%.1f %.1f) rotate(%d)"><title>%s — %d ft</title><path d="M0 -3.8 L2.8 3.5 L0 1.6 L-2.8 3.5 Z" fill="#ef5d6c" stroke="#fff7ed" stroke-width=".8"/></g>', $x,
                    $y, $heading, $callsign, (int) $pilot['altitude']);
            })->implode('');

            $controllerMarkers = collect($snapshot['controllers'])->map(function (array $controller) use ($project): string {
                [$x, $y] = $project((float) $controller['latitude'], (float) $controller['longitude']);
                $icao = e((string) $controller['icao']);

                return sprintf('<g transform="translate(%.1f %.1f)"><title>%s controller online</title><circle r="4" fill="#ef5d6c" stroke="#ffd8dc" stroke-width="1.5"/><circle r="1.4" fill="#ffffff"/></g>', $x, $y, $icao);
            })->implode('');

            $pilotCount = count($snapshot['pilots']);
            $controllerCount = count($snapshot['controllers']);

            return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" role="img" aria-label="Live VATSIM traffic map of Germany">
  <defs>
    <linearGradient id="background" x1="0" x2="1" y1="0" y2="1"><stop stop-color="#162a42"/><stop offset="1" stop-color="#294968"/></linearGradient>
    <pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse"><path d="M32 0H0V32" fill="none" stroke="#dbe7f2" stroke-opacity=".08"/></pattern>
    {$aircraftSymbols}
  </defs>
  <rect width="{$width}" height="{$height}" rx="28" fill="url(#background)"/>
  <rect width="{$width}" height="{$height}" rx="28" fill="url(#grid)"/>
  <path d="{$outlinePath}" fill="#213f5d" stroke="#91b1cb" stroke-opacity=".82" stroke-width="2.2" fill-rule="evenodd"/>
  {$pilotMarkers}
  {$controllerMarkers}
</svg>
SVG;
        });
    }

    private function isWithinGermany(float $latitude, float $longitude): bool
    {
        return $latitude >= 47.0 && $latitude <= 55.2 && $longitude >= 5.4 && $longitude <= 15.6;
    }

    private function aircraftAssetMarkup(string $asset): string
    {
        $svg = file_get_contents(public_path('images/brand/aircraft/'.$asset));
        if (! is_string($svg)) {
            return '';
        }

        $svg = preg_replace('/^.*?<svg\\b[^>]*>/s', '', $svg, 1) ?? '';

        return preg_replace('/<\\/svg>\\s*$/s', '', $svg) ?? '';
    }

    private function isAirborne(int $altitude, int $groundspeed): bool
    {
        return $altitude >= 500 || $groundspeed >= 80;
    }

    /**
     * Build the actual German national border (including islands) from the
     * low-detail, open GeoJSON dataset bundled with the application.
     * Source: isellsoap/deutschlandGeoJSON, 4_niedrig.geo.json.
     *
     * @param  callable(float, float): array{0: float, 1: float}  $project
     */
    private function germanyOutlinePath(callable $project): string
    {
        /** @var array{features: array<int, array{geometry: array{coordinates: array<int, array<int, array<int, array<int, float>>>>}}> } $geoJson */
        $geoJson = json_decode(
            file_get_contents(resource_path('geodata/germany-low.geo.json')) ?: '{"features":[]}',
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        return collect($geoJson['features'][0]['geometry']['coordinates'] ?? [])
            ->map(function (array $polygon) use ($project): string {
                $ring = $polygon[0] ?? [];

                return collect($ring)->map(function (array $coordinate, int $index) use ($project): string {
                    [$x, $y] = $project((float) $coordinate[1], (float) $coordinate[0]);

                    return ($index === 0 ? 'M' : 'L').round($x, 1).' '.round($y, 1);
                })->implode(' ').' Z';
            })
            ->implode(' ');
    }
}
