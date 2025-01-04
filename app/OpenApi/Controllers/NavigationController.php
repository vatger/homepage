<?php

namespace App\OpenApi\Controllers;

use App\Models\Navigation\Station;

class NavigationController extends ApiController
{
    public function stationList(): array
    {
        return Station::all();
    }

    public function stationView(string $ident)
    {
        $station = Station::where('ident', 'LIKE', $ident)->firstOrFail();
        $station?->makeHidden(['created_at', 'updated_at']);

        return $station;
    }
}
