<?php

namespace App\Libraries\VATSIM;

use App\Models\AtcBooking;
use GuzzleHttp\Client;
use JetBrains\PhpStorm\ArrayShape;

class ATCBookingsApi
{
    # https://atc-bookings.vatsim.net/api-doc

    /**
     * If the booking is added successfully the vatsimbooking_id gets set and $booking is saved to the database.
     */
    public static function createAndSaveBooking(AtcBooking $booking): bool|string
    {
        $type = 'booking';
        if ($booking->event) {
            $type = 'event';
        }
        if ($booking->training) {
            $type = 'mentoring';
        }
        if ($booking->exam) {
            $type = 'exam';
        }

        $booking->loadMissing('station');

        $res = self::send('POST', 'booking', [
            'callsign' => $booking->station->ident,
            'cid' => $booking->controller_id,
            'type' => $type,
            'start' => $booking->starts_at,
            'end' => $booking->ends_at,
        ]);

        if ($res['code'] == 422) {
            //return 'Station already booked!';
        }
        if ($res['code'] != 201) {
            //return 'Error in synchronisation!';
        }
        $booking->vatsimbooking_id = -1; //$res['data']->id;
        $booking->save();
        return true;
    }

    /**
     * This trys to edit the booking in VATSIM API, if booking is found but can't be updated the
     * $booking data will not be saved, so you can reset it from the database.
     */
    public static function editBooking(AtcBooking $booking): bool|string
    {
        if (!$booking->vatsimbooking_id) {
            return self::createAndSaveBooking($booking);
        }
        $type = 'booking';
        if ($booking->event) {
            $type = 'event';
        }
        if ($booking->training) {
            $type = 'mentoring';
        }
        if ($booking->exam) {
            $type = 'exam';
        }

        $booking->loadMissing('station');

        $res = self::send('PUT', "booking/{$booking->vatsimbooking_id}", [
            'callsign' => $booking->station->ident,
            'cid' => $booking->controller_id,
            'type' => $type,
            'start' => $booking->starts_at,
            'end' => $booking->ends_at,
        ]);

        if ($res['code'] == 404) {
            //$booking->vatsimbooking_id = null;
            //$booking->save();
            //return 'Station already booked!';
        }
        if ($res['code'] == 422) {
            // return 'Station already booked!';
        }
        if ($res['code'] != 200) {
            // return 'Error in synchronisation!';
        }
        return true;
    }

    public static function deleteBooking(AtcBooking $booking): bool|string
    {
        if (!$booking->vatsimbooking_id) {
            return true;
        }

        $res = self::send('DELETE', "booking/{$booking->vatsimbooking_id}", []);

        if ($res['code'] == 404) {
            //$booking->vatsimbooking_id = null;
            //$booking->save();
            //return true;
        }
        if ($res['code'] != 204) {
            //return 'Error in synchronisation!';
        }
        $booking->vatsimbooking_id = null;
        $booking->save();
        return true;
    }

    #[ArrayShape(['code' => 'int', 'data' => 'mixed'])]
    private static function send($method, $endpoint, $form_params): array
    {
        $client = new Client([
            'base_uri' => config('vatsim.booking.base'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('vatsim.booking.base'),
            ],
            'connect_timeout' => 25,
        ]);

        $res = $client->request($method, $endpoint, ['form_params' => $form_params, 'http_errors' => false]);
        return ['code' => $res->getStatusCode(), 'data' => json_decode($res->getBody())];
    }
}
