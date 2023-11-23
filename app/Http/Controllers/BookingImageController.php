<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookingImageController extends Controller
{
    public function serveBookingImage(Request $request, string $image_id): Response | JsonResponse | BinaryFileResponse
    {
        if (!Auth::check()) {
            return response()->file(storage_path("app/public/booking_image/error.png"));
        }

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
