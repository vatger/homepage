<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingImageController extends Controller
{
    public function serveBookingImage(Request $request, string $image_id) {
        // http required for internal traffic
        $url = "http://bookings.vatsim-germany.org/" . $image_id . "/?" . $request->getQueryString();

        $response = Http::get($url);

        if ($response->successful()) {
            $contentType = $response->header("Content-Type");

            return response($response->body())->header('Content-Type', $contentType);
        }

        return response()->json(['error' => 'Not found'], 404);
    }
}
