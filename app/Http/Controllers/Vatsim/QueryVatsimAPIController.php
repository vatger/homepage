<?php

namespace App\Http\Controllers\Vatsim;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Libraries\VATSIM\EventLibrary;
use App\Models\Navigation\Aerodrome;
use Illuminate\Http\Request;

class QueryVatsimAPIController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Queries the myVatsim event API and selects only german events giving back the first 6 results.
     * API Response is in ascending date, so order does not need to be checked.
     * Parsed response (6 Airports) data cached for 10 minutes (600s)
     *
     * API Endpoint: https://my.vatsim.net/api/v1/events/all
     *
     * @param Request $request
     * @return string
     */
    public function loadEvents(Request $request, int $count = 9): string
    {
        // Abort if request not ajax
        if (!$request->ajax()) {
            abort(403, 'No Ajax request supplied.');
        }

        return EventLibrary::getEvents($count);
    }

    /**
     * Queries the myVatsim event API and selects events for a given ICAO code.
     * Parsed response (1 Airport) data cached for 10 minutes (600s)
     *
     * API Endpoint: https://my.vatsim.net/api/v1/events/all
     *
     * @param Request $request
     * @return mixed|never
     */
    public function loadSingleEvent(Request $request): mixed
    {
        // Abort if request not ajax
        if (!$request->ajax()) {
            return abort(403, 'No Ajax request supplied.');
        }

        // Return event date, either cached (10 minutes), or by executing the function
        return EventLibrary::getAerodromeEvent($request->icao);
    }

    /**
     * Loads METAR for a given ICAO
     *
     * @param Request $request
     * @return bool|string
     */
    public function loadMetar(Request $request): bool|string
    {
        // Abort if request not ajax
        if (!$request->ajax()) {
            return abort(403, 'No Ajax request supplied.');
        }

        $icao = $request->get('icao');
        $metar = DataFeedLibrary::Metar($icao);

        if (!$metar) {
            return abort(500, 'Error parsing ICAO');
        }

        return $metar;
    }

    /**
     * Loads active ATC from the datafeed for a given ICAO
     *
     * @param Request $request
     * @return array
     */
    public function loadActiveAtc(Request $request): array
    {
        if (!$request->ajax()) {
            return abort(403, 'No Ajax request supplied.');
        }

        $icao = $request->route('icao');

        $airportPositions = Aerodrome::icao($icao)
            ->first()
            ->stations->sortBy('order');
        $activeControllers = DataFeedLibrary::Controllers();

        $res = [];

        // Quite inefficient, something to change in the future
        foreach ($activeControllers as $controller) {
            if (substr($controller->callsign, 0, 4) == $icao) {
                $res[] = $controller;
            } else {
                foreach ($airportPositions as $ap) {
                    $apIdentStart = substr($ap->ident, 0, 4);
                    $apIdentEnd = substr($ap->ident, -3);
                    if (
                        $ap->ident == $controller->callsign ||
                        ($ap->frequency == $controller->frequency &&
                            \preg_match('/' . $apIdentStart . '_[A-Za-z0-9]+_' . $apIdentEnd . '/', $controller->callsign))
                    ) {
                        $res[] = $controller;
                        break;
                    }
                }
            }
        }

        return $res;
    }
}
