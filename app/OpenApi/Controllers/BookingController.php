<?php

namespace App\OpenApi\Controllers;

use App\Models\AtcBooking;
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
}
