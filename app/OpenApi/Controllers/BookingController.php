<?php

namespace App\OpenApi\Controllers;

use App\Models\AtcBooking;
use App\Models\Navigation\Station;
use App\OpenApi\Helpers\ApiPathfinder;
use App\OpenApi\SecuritySchemes\TokenSecurityScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class BookingController extends ApiController
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
    #[ApiPathfinder('booking.index')]
    public function index(Request $request, string $start = '', string $end = ''): array
    {
        $this->authorizeApiRequest('booking.index');
        // construct the start and end times
        $s = !empty($start) ? Carbon::createFromFormat('Y-m-d', $start) : Carbon::now();
        $s->setTime(0, 0, 0);
        $e = !empty($end) ? Carbon::createFromFormat('Y-m-d', $end) : $s->copy();
        $e->setTime(23, 59, 59);
        // collect the bookings
        $bookings = AtcBooking::with(['station', 'controller'])
            ->whereBetween('starts_at', [$s, $e])
            ->orWhereBetween('ends_at', [$s, $e])
            //->orderBy('station.name')
            //->select(['starts_at', 'ends_at', 'voice', 'training', 'exam', 'event', 'station', 'controller'])
            ->get();

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

    /**
     * Allow the mass creation of bookings
     * // TODO: Move this to a booking Library such that we can use it from internally as well.
     *
     * @param Request $request
     * @return array
     */
    #[OpenApi\Operation(security: TokenSecurityScheme::class)]
    #[ApiPathfinder('booking.create')]
    public function createMassBookings(Request $request): array
    {
        $this->authorizeApiRequest('booking.create');

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'stations' => 'required'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        $not_found = [];
        foreach ($request->stations as $station) {
            $s = Station::query()->where('ident', 'LIKE', $station)->select('id')->first();
            if ($s == null) {
                $not_found[] = $station;
                continue;
            };

            // TODO: Check for overlaps
            // TODO: Abort on single overlap!
            AtcBooking::query()->create([
                'station_id' => $s->id,
                'controller_id' => '1',
                'starts_at' => $start,
                'ends_at' => $end,
                'event' => true,
            ]);
        }

        return [
            'error' => count($not_found) > 0,
            'missing' => $not_found
        ];
    }
}
