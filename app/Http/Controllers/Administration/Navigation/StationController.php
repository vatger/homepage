<?php

namespace App\Http\Controllers\Administration\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StationController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Station::class);

        $stations = Station::query()->paginate(15);

        return $this->prepareView('administration.navigation.stations.index')->with('stations', $stations);
    }

    public function getStationsPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('viewAny', Station::class);

        return Station::query()->paginate(15);
    }

    public function getStationsSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('viewAny', Station::class);

        return Station::query()
            ->where('name', 'LIKE', '%' . $request->get('search_param') . '%')
            ->orWhere('ident', 'LIKE', '%' . $request->get('search_param') . '%')
            ->orWhere('frequency', 'LIKE', '%' . $request->get('search_param') . '%')
            ->get();
    }

    public function create(Request $request)
    {
        $this->authorize('create', Station::class);

        $aerodromes = Aerodrome::isDe()
            ->orderBy('icao')
            ->get();

        return $this->prepareView('administration.navigation.stations.create')->with('aerodromes', $aerodromes);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Station::class);

        $validated = $request->validate([
            'name' => 'required',
            'ident' => ['required', Rule::unique('navigation_stations', 'ident')],
            'frequency' => 'required',
            'aerodromes' => 'nullable|array',
            'bookable' => 'required|boolean',
            'atis' => 'required|boolean',
        ]);

        $station = Station::create($validated);
        $station->fresh();
        $station->aerodromes()->attach($validated['aerodromes']);

        return redirect()
            ->route('administration.navigation.stations.view', ['station' => $station])
            ->withSuccess('Station created successfully!');
    }

    public function show(Request $request, Station $station)
    {
        $this->authorize('view', $station);

        $station->loadMissing('aerodromes');
        $aerodromes = Aerodrome::isDe()
            ->orderBy('icao')
            ->get();

        return $this->prepareView('administration.navigation.stations.show')
            ->with('station', $station)
            ->with('aerodromes', $aerodromes);
    }

    public function update(Request $request, Station $station)
    {
        $this->authorize('update', $station);

        $validated = $request->validate([
            'name' => 'required',
            'ident' => ['required', Rule::unique('navigation_stations', 'ident')->ignore($station->id)],
            'frequency' => 'required',
            'aerodromes' => 'nullable|array',
            'bookable' => 'required|boolean',
            'atis' => 'required|boolean',
        ]);

        $station->update($validated);

        $station->aerodromes()->sync($validated['aerodromes']);

        return redirect()
            ->route('administration.navigation.stations.view', ['station' => $station])
            ->withSuccess('Station updated successfully!');
    }

    public function delete(Request $request, Station $station)
    {
        $this->authorize('delete', $station);

        $station->aerodromes()->detach();

        $station->delete();

        return redirect()
            ->route('administration.navigation.stations')
            ->withSuccess('Station removed successfully!');
    }
}
