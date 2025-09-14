<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingImageController extends Controller
{
    private static string $BOOKING_COOKIE_NAME = 'vatsim_germany_booking_theme';

    private static array $HEADERS = [
        'Expires' => 0,
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-store, max-age=0',
        'Cache-directive' => 'no-cache',
        'Pragma-directive' => 'no-cache',
        'Content-Type' => 'image/png',
    ];

    public function serveBookingImage(Request $request, string $image_id): \Illuminate\Http\Response|StreamedResponse|BinaryFileResponse
    {
        if (! Auth::check()) {
            if ($this->_getDisplayMode($request) == 'dark') {
                return response()->file(storage_path('app/public/booking_image/error_dark.png'), self::$HEADERS);
            }

            return response()->file(storage_path('app/public/booking_image/error.png'), self::$HEADERS);
        }

        $client = new Client;
        $url = 'http://bookings.vatsim-germany.org/'.$image_id.'/?theme='.$this->_getDisplayMode($request).'&'.$request->getQueryString();
        try {
            $response = $client->get($url, ['stream' => true]);

            if ($response->getStatusCode() === 200) {
                $stream = $response->getBody(); // This is a Psr7 Stream

                return Response::stream(function () use ($stream) {
                    while (! $stream->eof()) {
                        echo $stream->read(1024); // read in chunks
                        flush(); // ensure data is sent immediately
                    }
                }, 200, self::$HEADERS);
            }
        } catch (GuzzleException $e) {
        }

        return response('Error fetching image', 404);
    }

    public function setDarkMode(Request $request): JsonResponse
    {
        $cookie = cookie(self::$BOOKING_COOKIE_NAME, 'dark', Carbon::now()->addYear()->diffInMinutes(Carbon::now(), true));

        return response()->json(['message' => 'Dark Mode Set!'])->withCookie($cookie);
    }

    public function setLightMode(Request $request): JsonResponse
    {
        $cookie = cookie(self::$BOOKING_COOKIE_NAME, 'light', Carbon::now()->addYear()->diffInMinutes(Carbon::now(), true));

        return response()->json(['message' => 'Light Mode Set!'])->withCookie($cookie);
    }

    private function _getDisplayMode(Request $request): string
    {
        if ($request->hasCookie(self::$BOOKING_COOKIE_NAME)) {
            return $request->cookie(self::$BOOKING_COOKIE_NAME);
        }

        return 'light';
    }
}
