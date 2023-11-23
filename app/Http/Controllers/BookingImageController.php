<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookingImageController extends Controller
{
    private static string $BOOKING_COOKIE_NAME = "vatsim_germany_booking_theme";

    public function serveBookingImage(Request $request, string $image_id): Response | JsonResponse | BinaryFileResponse
    {
        if (!Auth::check()) {
            if ($this->_getDisplayMode($request) == "dark") {
                return response()->file(storage_path('app/public/booking_image/error_dark.png'));
            }

            return response()->file(storage_path("app/public/booking_image/error.png"));
        }

        // http required for internal traffic
        $url = "http://bookings.vatsim-germany.org/" . $image_id . "/?theme=" . $this->_getDisplayMode($request) . "&" . $request->getQueryString();

        $response = Http::get($url);

        if ($response->successful()) {
            $contentType = $response->header("Content-Type");

            return response($response->body())->header('Content-Type', $contentType);
        }

        return response()->json(['error' => 'Not found'], 404);
    }

    public function setDarkMode(Request $request): JsonResponse
    {
        if (!$this->_checkCookieConsent($request)) {
            return response()->json(['message' => 'Cookie Consent not given. Can\'t save preferences.']);
        }

        $cookie = cookie(self::$BOOKING_COOKIE_NAME, 'dark', Carbon::now()->addYear()->diffInMinutes(Carbon::now()));
        return response()->json(['message' => 'Dark Mode Set!'])->withCookie($cookie);
    }

    public function setLightMode(Request $request): JsonResponse
    {
        if (!$this->_checkCookieConsent($request)) {
            return response()->json(['message' => 'Cookie Consent not given. Can\'t save preferences.']);
        }

        $cookie = cookie(self::$BOOKING_COOKIE_NAME, 'light', Carbon::now()->addYear()->diffInMinutes(Carbon::now()));
        return response()->json(['message' => 'Light Mode Set!'])->withCookie($cookie);
    }

    private function _getDisplayMode(Request $request): string
    {
        if ($request->hasCookie(self::$BOOKING_COOKIE_NAME)) {
            return $request->cookie(self::$BOOKING_COOKIE_NAME);
        }

        return 'light';
    }

    private function _checkCookieConsent(Request $request): bool
    {
        return $request->cookie('vatger_cookie_consent') != null;
    }
}
