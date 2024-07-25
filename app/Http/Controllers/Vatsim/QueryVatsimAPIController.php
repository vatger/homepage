<?php

namespace App\Http\Controllers\Vatsim;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;
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
     * Parsed response ($count number of Airports) data cached for 10 minutes (600s)
     *
     * API Endpoint: https://my.vatsim.net/api/v1/events/all
     *
     * @param Request $request
     * @param int $count
     * @return string
     */
    public function loadEvents(Request $request, int $count = 9): string
    {
        return EventLibrary::getEvents($count, true);
    }

    /**
     * Queries the myVatsim event API and selects events for a given ICAO code.
     * Parsed response (1 Airport) data cached for 10 minutes (600s)
     *
     * API Endpoint: https://my.vatsim.net/api/v1/events/all
     *
     * @param Request $request
     * @return mixed
     */
    public function loadSingleEvent(Request $request): mixed
    {
        // Return event date, either cached (10 minutes), or by executing the function
        return EventLibrary::getAerodromeEvent($request->get('icao'));
    }
}
