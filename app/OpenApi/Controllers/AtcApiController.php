<?php

namespace App\OpenApi\Controllers;

use App\Models\AtcBooking;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class AtcApiController extends ApiController
{
    /**
     * Retrieve a collection of AtcBooking
     * between a given start and end date
     *
     * @param Request $request
     * @param string $start The start date in format Y-m-d
     * @param string $end The end date in format Y-m-d
     * @return array
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    public function index(Request $request, string $start = '', string $end = ''): array
    {
        // construct the start and end times
        $s = !empty($start) ? Carbon::createFromFormat('Y-m-d', $start) : Carbon::now();
        $s->setTime(0, 0, 0);
        $e = !empty($end) ? Carbon::createFromFormat('Y-m-d', $end) : $s->copy();
        $e->setTime(23, 59, 59);
        // collect the bookings
        $bookings = AtcBooking::with(['station', 'controller'])
            //->whereBetween('starts_at', [$s, $e])
            //->orWhereBetween('ends_at', [$s, $e])
            //->orderBy('station.name')
            //->select(['starts_at', 'ends_at', 'voice', 'training', 'exam', 'event', 'station', 'controller'])
            ->get();

        /*
        $bookings = collect();
        // Let's grab bookings for GERMAN airports
        if (Auth::check()) {
            // Authenticated user. We can show controller names
            foreach (
                Aerodrome::isDe()
                    ->orderBy('icao', 'ASC')
                    ->orderBy('major')
                    ->with([
                        'stations' => function ($query) use ($s, $e) {
                            return $query->with([
                                'bookings' => function ($query) use ($s, $e) {
                                    return $query
                                        ->whereBetween('starts_at', [$s, $e])
                                        ->orWhereBetween('ends_at', [$s, $e])
                                        ->with('controller');
                                },
                            ]);
                        },
                    ])
                    ->get()
                as $aerodrome
            ) {
                $bookings->push($aerodrome);
            }
        } else {
            // We must hide ( not load at all ) controller names
            foreach (
                Aerodrome::isDe()
                    ->orderBy('icao', 'ASC')
                    ->orderBy('major')
                    ->with([
                        'stations' => function ($query) use ($s, $e) {
                            return $query->with([
                                'bookings' => function ($query) use ($s, $e) {
                                    return $query->whereBetween('starts_at', [$s, $e])->orWhereBetween('ends_at', [$s, $e]);
                                },
                            ]);
                        },
                    ])
                    ->get()
                as $aerodrome
            ) {
                $bookings->push($aerodrome);
            }
        }

        $filtered = $bookings->filter(function ($value, $key) {
            if (count($value->stations) > 0) {
                foreach ($value->stations as $station) {
                    if (count($station->bookings) > 0) {
                        return true;
                    }
                }
            }
            return false;
        });
        return $filtered->flatten();
        */

        $bookings = $bookings->map(function ($b) {
            $n = [
                'starts_at' => $b->starts_at,
                'ends_at' => $b->ends_at,
                'voice' => (bool) $b->voice,
                'training' => (bool) $b->training,
                'exam' => (bool) $b->exam,
                'event' => (bool) $b->event,
                'station' => [
                    'ident' => $b->station?->ident,
                    'name' => $b->station?->name,
                    'frequency' => $b->station?->fixedFrequency,
                ],
                'controller' => [
                    'ident' => $b->controller?->id,
                    'username' => $b->controller?->username,
                    'username_short' => $b->controller?->username_short,
                ],
            ];
            return (object) $n;
        });

        return $bookings->toArray();
    }
}
