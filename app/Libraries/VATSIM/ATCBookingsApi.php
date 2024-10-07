<?php

namespace App\Libraries\VATSIM;

use App\Models\AtcBooking;
use App\Models\Membership\User;
use GuzzleHttp\Client;

class ATCBookingsApi
{
    # https://atc-bookings.vatsim.net/api-doc

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

        $type = 'booking';
        if ($booking->event) {
            $type = 'event';
        }
        if ($booking->training) {
            $type = 'training';
        }
        //if ($booking->exam) {
        //    $type = 'exam';
        //}

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

    /**
     * This trys to edit the booking in VATSIM API, if booking is found but can't be updated the
     * $booking data will not be saved, so you can reset it from the database.
     */
    public static function editBooking(AtcBooking $booking): array
    {
        if (!$booking->vatsim_booking_id) {
            return self::createAndSaveBooking($booking);
        }
        $type = 'booking';
        if ($booking->event) {
            $type = 'event';
        }
        if ($booking->training) {
            $type = 'mentoring';
        }
        //if ($booking->exam) {
        //    $type = 'exam';
        //}

        $booking->loadMissing('station');

        $res = self::send('PUT', "booking/{$booking->vatsim_booking_id}", [
            'callsign' => $booking->station->ident,
            'cid' => $booking->controller_id,
            'type' => $type,
            'start' => $booking->starts_at->toDateTimeString(),
            'end' => $booking->ends_at->toDateTimeString(),
        ]);

        if ($res['code'] == 404) {
            $booking->vatsim_booking_id = null;
            $booking->save();
            return [
                'ok' => false,
                'message' => 'Booking updated but VATSIM sync failed.',
            ];
        }
        if ($res['code'] == 422) {
            $booking->refresh();
            return [
                'ok' => false,
                'message' => 'Station already booked!',
            ];
        }
        if ($res['code'] != 200) {
            $booking->refresh();
            return [
                'ok' => false,
                'message' => 'Error in synchronisation!',
            ];
        }
        $booking->save();
        return [
            'ok' => true,
            'message' => 'Booking updated!',
        ];
    }

    public static function deleteBooking(AtcBooking $booking): array
    {
        if (!$booking->vatsim_booking_id) {
            $booking->delete();
            return [
                'ok' => true,
                'message' => 'Local booking deleted!',
            ];
        }

        $res = self::send('DELETE', "booking/{$booking->vatsim_booking_id}", []);

        if ($res['code'] == 404) {
            $booking->delete();
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
        return [
            'ok' => true,
            'message' => 'Booking deleted!',
        ];
    }

    private static function send(string $method, string $endpoint, array $form_params): array
    {
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('vatsim.booking.token'),
            ],
            'connect_timeout' => 25,
        ]);

        $url = config('vatsim.booking.base') . '/' . $endpoint;

        $res = $client->request($method, $url, ['form_params' => $form_params, 'http_errors' => false]);
        return ['code' => $res->getStatusCode(), 'data' => json_decode($res->getBody())];
    }
}
