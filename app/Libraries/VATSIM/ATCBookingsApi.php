<?php

namespace App\Libraries\VATSIM;

use App\Models\AtcBooking;
use App\Models\Membership\User;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ATCBookingsApi
{
    // https://atc-bookings.vatsim.net/api-doc

    public static function deleteBookingsInTheWayForVatgerEvent(AtcBooking $b): int
    {
        if (! $b->vatger_event) {
            return 0;
        }

        $bookingsInTheWay = AtcBooking::query()
            ->where('vatger_event', false)
            ->where('station_id', $b->station_id)
            ->where('starts_at', '<', $b->ends_at)
            ->where('ends_at', '>', $b->starts_at)
            ->get();

        $deletedCount = 0;

        foreach ($bookingsInTheWay as $booking) {
            self::deleteBooking($booking);
            $deletedCount++;
        }

        return $deletedCount;
    }

    public static function checkBooking(AtcBooking $b): ?string
    {
        // No checks for vatger_event
        if ($b->vatger_event) {
            return null;
        }

        $already_controller = DataFeedLibrary::Controller($b->station);
        $allowed_start = Carbon::now()->addHours(0.5);
        if ($b->starts_at->isBefore($allowed_start)) {
            if ($already_controller && $already_controller->cid == Auth::user()->id) {
                return null;
            }

            return "You can't book a station this close to the start.";
        }

        $allowed_start = Carbon::now()->addHours(1.5);
        if ($already_controller && $b->starts_at->isBefore($allowed_start)) {
            if ($already_controller->cid == Auth::user()->id) {
                return null;
            }

            return "You can't book this station. There is someone already connected to this station.";
        }

        $overlappingBookingExists = AtcBooking::query()
            ->where('controller_id', Auth::id())
            ->where('vatger_event', false)
            ->where('starts_at', '<', $b->ends_at)
            ->where('ends_at', '>', $b->starts_at)
            ->exists();

        if ($overlappingBookingExists) {
            return "You can't book more than one station at the same time.";
        }

        if (! $b->training && $b->starts_at->isFuture()) {
            $futureBookingsCount = AtcBooking::query()
                ->where('controller_id', Auth::id())
                ->where('training', false)
                ->where('vatger_event', false)
                ->where('starts_at', '>', Carbon::now())
                ->count();

            if ($futureBookingsCount >= 3) {
                return "You can't have more than 3 future bookings.";
            }
        }

        return null;
    }

    /**
     * If the booking is added successfully the vatsimbooking_id gets set and $booking is saved to the database.
     */
    public static function createAndSaveBooking(AtcBooking $booking): array
    {
        if (User::find($booking->controller_id)?->vatsimDetails?->rating_atc <= 1) {
            return [
                'ok' => false,
                'message' => 'Can not book, you need an ATC Rating!',
            ];
        }
        if ($booking->vatger_event && $booking->training) {
            return [
                'ok' => false,
                'message' => 'Can not book, a vatger event can never be a training!',
            ];
        }

        $type = 'booking';
        if ($booking->event || $booking->vatger_event) {
            $type = 'event';
        }
        if ($booking->training) {
            $type = 'training';
        }
        // if ($booking->exam) {
        //    $type = 'exam';
        // }

        $booking->loadMissing('station');

        $res = self::send('POST', 'booking', [
            'callsign' => $booking->station->ident,
            'cid' => $booking->controller_id,
            'type' => $type,
            'start' => $booking->starts_at->toDateTimeString(),
            'end' => $booking->ends_at->toDateTimeString(),
        ]);

        if ($res['code'] == 422) {
            return [
                'ok' => false,
                'message' => 'Station already booked!',
            ];
        }
        if ($res['code'] != 201) {
            $booking->vatsim_booking_id = null;
            $booking->save();

            return [
                'ok' => false,
                'message' => 'Error in synchronisation!',
            ];
        }
        $booking->vatsim_booking_id = $res['data']->id;
        $booking->save();

        return [
            'ok' => true,
            'message' => 'Booked.',
        ];
    }

    public static function deleteBooking(AtcBooking $booking): array
    {
        if (! $booking->vatsim_booking_id) {
            $booking->delete();
            self::deleteNotify($booking);

            return [
                'ok' => true,
                'message' => 'Local booking deleted!',
            ];
        }

        $res = self::send('DELETE', "booking/{$booking->vatsim_booking_id}", []);

        if ($res['code'] == 404) {
            $booking->delete();
            self::deleteNotify($booking);

            return [
                'ok' => true,
                'message' => 'Local booking deleted!',
            ];
        }
        if ($res['code'] != 204) {
            return [
                'ok' => false,
                'message' => 'Error in synchronisation!',
            ];
        }
        $booking->delete();
        self::deleteNotify($booking);

        return [
            'ok' => true,
            'message' => 'Booking deleted!',
        ];
    }

    private static function deleteNotify(AtcBooking $booking): void
    {
        if ($booking->ends_at->isPast()) {
            return;
        }
        $controller = $booking->controller;
        $n = new BasicNotification(
            __('booking.atc.create.delete-title'),
            __('booking.atc.create.delete-text', [
                'STATION' => $booking->station->name.' ('.$booking->station->ident.')',
                'START' => $booking->starts_at->toDateTimeString(),
                'END' => $booking->ends_at->toDateTimeString(),
            ]),
            'vatger BOOKING System');
        $controller->notify($n);
    }

    private static function send(string $method, string $endpoint, array $form_params): array
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.config('vatsim.booking.token'),
            ],
            'connect_timeout' => 25,
        ]);

        $url = config('vatsim.booking.base').'/'.$endpoint;

        $res = $client->request($method, $url, ['form_params' => $form_params, 'http_errors' => false]);

        return ['code' => $res->getStatusCode(), 'data' => json_decode($res->getBody())];
    }
}
