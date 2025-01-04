<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

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

    public function serveBookingImage(Request $request, string $image_id): Response|JsonResponse|BinaryFileResponse
    {
        if (! Auth::check()) {
            if ($this->_getDisplayMode($request) == 'dark') {
                return response()->file(storage_path('app/public/booking_image/error_dark.png'), self::$HEADERS);
            }

            return response()->file(storage_path('app/public/booking_image/error.png'), self::$HEADERS);
        }

        // http required for internal traffic
        $url = 'http://bookings.vatsim-germany.org/'.$image_id.'/?theme='.$this->_getDisplayMode($request).'&'.$request->getQueryString();

        $response = Http::get($url);

        if ($response->successful()) {
            return response($response->body(), ResponseCodes::HTTP_OK, self::$HEADERS);
        }

        return response()->json(['error' => 'Not found'], 404);
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
