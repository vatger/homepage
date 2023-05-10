<?php

namespace App\Http\Controllers\Administration\Prevent;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use Events\EventRoute;
use Events\RouteLeg;
use Illuminate\Http\Request;

class EventroutesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->prepareView('administration.prevent.route.index')->with('routes', EventRoute::all());
    }

    public function index1()
    {
        return $this->prepareView('administration.prevent.routedev.index')->with('routes', EventRoute::all());
    }

    public function show(Request $request, EventRoute $eventRoute)
    {
        $eventRoute->loadMissing('legs.accounts', 'legs.arrival', 'legs.departure');

        return $this->prepareView('administration.prevent.route.showlegs')->with('eventRoute', $eventRoute);
    }

    public function showdev(Request $request, EventRoute $eventRoute)
    {
        $eventRoute->loadMissing('legs.accounts', 'legs.arrival', 'legs.departure');

        return $this->prepareView('administration.prevent.routedev.showdetails')->with('eventRoute', $eventRoute);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Access denied!');
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'begins_at' => 'required|date',
            'ends_at' => 'required|date',
            'description' => 'required|string',
            'flight_rules' => 'nullable|string|size:1',
            'aircrafts' => 'nullable|string',
            'link' => 'string|nullable',
            'img_url' => 'string|nullable',
            'visible' => 'required|boolean',
            'require_order' => 'required|boolean',
        ]);

        $validated['link'] = empty($validated['link']) ? '' : $validated['link'];
        $validated['img_url'] = empty($validated['img_url']) ? '' : $validated['img_url'];

        $eventRoute = EventRoute::create($validated);

        return $eventRoute;
    }

    public function editRoute(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Access denied!');
        }

        $validated = $request->validate([]);

        return null;
    }

    public function delete(Request $request, EventRoute $eventRoute)
    {
        if (!$request->ajax()) {
            abort(403, 'Access denied');
        }

        //$eventRoute->accounts()->detach();
        //Foreach leg account detach check!
        //TODO: Alle Legs löschen

        $eventRoute->delete();
    }

    public function storeLeg(Request $request, EventRoute $eventRoute)
    {
        if (!$request->ajax()) {
            abort(403, 'Access denied!');
        }

        $validated = $request->validate([
            'arrival' => 'required|string|exists:navigation_aerodromes,icao',
            'departure' => 'required|string|exists:navigation_aerodromes,icao',
        ]);

        $leg = RouteLeg::create([
            'route_id' => $eventRoute->id,
            'departureaerodrome_id' => Aerodrome::where('icao', $validated['departure'])
                ->select('id')
                ->first()->id,
            'arrivalaerodrome_id' => Aerodrome::where('icao', $validated['arrival'])
                ->select('id')
                ->first()->id,
        ]);

        foreach ($eventRoute->accounts as $a) {
            $leg->accounts()->attach($a->id);
        }

        return $leg;
    }

    public function deleteLeg(Request $request, EventRoute $eventRoute)
    {
        if (!$request->ajax()) {
            abort(403, 'Access denied!');
        }

        $leg = RouteLeg::findOrFail($request->leg_id);

        $leg->accounts()->detach();

        $leg->delete();
    }

    public function getaccounts(Request $request, EventRoute $eventRoute)
    {
        $eventRoute->loadMissing('legs.accounts', 'legs.arrival', 'legs.departure');

        return $this->prepareView('administration.prevent.route.showaccounts')->with('eventRoute', $eventRoute);
    }
}
