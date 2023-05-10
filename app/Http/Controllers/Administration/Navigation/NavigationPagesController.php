<?php

namespace App\Http\Controllers\Administration\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): View
    {
        return $this->prepareView('administration.navigation.dashboard')
            ->with(
                'aerodromes',
                Aerodrome::isDe()
                    ->orderBy('icao', 'ASC')
                    ->get(),
            )
            ->with('stations', Station::orderBy('ident', 'ASC')->get());
    }
}
