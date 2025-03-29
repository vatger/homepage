<?php

namespace App\Http\Controllers\Api;

use App\Decorators\ApiPathfinder;
use App\Models\AtcBooking;
use App\Models\Membership\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Response;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class BookingController extends ApiController
{
    /**
     * Retrieve a collection of AtcBooking
     * between a given start and end date
     *
     * @param  string  $start  The start date in format Y-m-d
     * @param  string  $end  The end date in format Y-m-d
     */
    #[ApiPathfinder('booking.index')]
    public function index(Request $request, string $start = '', string $end = ''): array
    {
        $this->authorizeApiRequest('booking.index');
        // construct the start and end times
        $s = ! empty($start) ? Carbon::createFromFormat('Y-m-d', $start) : Carbon::now();
        $s->setTime(0, 0, 0);
        $e = ! empty($end) ? Carbon::createFromFormat('Y-m-d', $end) : $s->copy();
        $e->setTime(23, 59, 59);
        // collect the bookings
        $bookings = AtcBooking::with(['station', 'controller'])
            ->whereBetween('starts_at', [$s, $e])
            ->orWhereBetween('ends_at', [$s, $e])
            // ->orderBy('station.name')
            // ->select(['starts_at', 'ends_at', 'voice', 'training', 'exam', 'event', 'station', 'controller'])
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
     * Retrieve an icalender
     *
     * @return string
     */
    public function ical(Request $request, string $id, string $token)
    {
        $user = User::find($id);
        if (! $user) {
            abort(404);
        }
        if ($user->passwords->ical_token == null || $user->passwords->ical_token != $token) {
            abort(403);
        }

        $calendar_string = Cache::remember('api.booking.ical.'.$user->id, 60 * 10, function () use ($user) {
            $calendar = Calendar::create('VATSIM Germany Bookings')->refreshInterval(60);
            $events = [];

            $bookings = AtcBooking::with(['station', 'controller'])->where('controller_id', $user->id)->get();

            foreach ($bookings as $booking) {
                $events[] = Event::create('ATC Booking '.$booking->station->ident)
                    ->startsAt($booking->starts_at)
                    ->endsAt($booking->ends_at)
                    ->description('VATSIM Germany Booking of '.$booking->station->name.' on '.$booking->station->fixed_frequency.' kHz');
            }

            $calendar->event($events);

            return $calendar->get();
        });
        \Debugbar::disable();

        return Response::make($calendar_string)
            ->header('Content-type', 'text/calendar; charset=utf-8')
            ->header('Content-disposition', 'attachment; filename="calendar.ics"');
    }
}
