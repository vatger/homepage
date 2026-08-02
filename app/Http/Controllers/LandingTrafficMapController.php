<?php

namespace App\Http\Controllers;

use App\Services\LandingTrafficService;
use Symfony\Component\HttpFoundation\Response;

class LandingTrafficMapController extends Controller
{
    public function __invoke(LandingTrafficService $traffic): Response
    {
        return response($traffic->svg(), Response::HTTP_OK, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=60',
        ]);
    }
}
