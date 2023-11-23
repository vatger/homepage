<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class BookingImageController extends Controller
{
    public function serveBookingImage(string $image_id) {
        $response = Http::get("http://bookings.vatsim-germany.org/" . $image_id . "/");

        if ($response->successful()) {
            $contentType = $response->header("Content-Type");

            return response($response->body())->header('Content-Type', $contentType);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
