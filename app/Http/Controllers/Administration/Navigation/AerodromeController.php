<?php

namespace App\Http\Controllers\Administration\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Chart;
use App\Models\Navigation\Station;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AerodromeController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Display all available aerodromes that can be changed by us
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Aerodrome::class);

        $aerodromes = Aerodrome::isDe()
            ->orderBy('icao', 'ASC')
            ->paginate(15);

        return $this->prepareView('administration.navigation.aerodromes.index')->with('aerodromes', $aerodromes);
    }

    /**
     * @param Request $request
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getAerodromesPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('viewAny', Aerodrome::class);

        return Aerodrome::isDe()
            ->orderBy('icao', 'ASC')
            ->paginate(15);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getAerodromesSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('viewAny', Aerodrome::class);

        return Aerodrome::query()
            ->where('icao', 'LIKE', '%' . $request->get('search_param') . '%')
            ->orWhere('iata', 'LIKE', '%' . $request->get('search_param') . '%')
            ->orWhere('name', 'LIKE', '%' . $request->get('search_param') . '%')
            ->orderBy('icao', 'ASC')
            ->isDe()
            ->get();
    }

    /**
     * Display the administration interface for the requested aerodrome
     *
     * @param Request $request
     * @param Aerodrome $aerodrome
     * @return View
     */
    public function show(Request $request, Aerodrome $aerodrome): View
    {
        $this->authorize('view', $aerodrome);

        $aerodrome->loadMissing('stations', 'runways', 'charts');

        $charts = $this->_getCharts($aerodrome)->toArray();
        // Sort charts by name
        usort($charts, function ($a, $b) {
            if (is_array($a) && is_array($b)) {
                return strnatcmp($a['name'], $b['name']);
            } elseif (!is_array($a) && is_array($b)) {
                return strnatcmp($a->name, $b['name']);
            } elseif (is_array($a) && !is_array($b)) {
                return strnatcmp($a['name'], $b->name);
            } else {
                return strnatcmp($a->name, $b->name);
            }
        });

        return $this->prepareView('administration.navigation.aerodromes.show')
            ->with('aerodrome', $aerodrome)
            ->with('chartsCombined', $charts)
            ->with('stations', Station::orderBy('ident', 'ASC')->get());
    }

    /**
     * Upload an aerodrome header image to the public storage path
     *
     * @param Request $request
     * @param Aerodrome $aerodrome
     *
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Aerodrome $aerodrome): JsonResponse
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('update', $aerodrome);

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg|max:2048|nullable', // This is only needed for the header image
            'name' => 'nullable|string',
            'icao' => 'nullable|string|size:4',
            'iata' => 'nullable|string|size:3',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'civilian' => 'nullable|boolean',
            'military' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'elevation' => 'nullable|numeric',
        ]);

        if ($request->has('image') && ($file = $request->file('image'))) {
            $fileName = strtolower($aerodrome->icao) . '.' . $request->image->getClientOriginalExtension();
            $file->move(public_path() . '/images/aerodromes', $fileName);

            return \response()->json(
                [
                    'image' => $fileName,
                ],
                ResponseAlias::HTTP_OK,
            );
        } else {
            try {
                $aerodrome->updateOrFail($request->all());
                return \response()->json(true, ResponseAlias::HTTP_OK);
            } catch (Exception $e) {
                return \response()->json($request->all(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }

    /**
     * Update the order of the Station models related to this aerodrome
     *
     * @param Request $request
     * @param Aerodrome $aerodrome
     *
     * @return Response
     */
    public function updateStationOrder(Request $request, Aerodrome $aerodrome): Response
    {
        $this->authorize('update', $aerodrome);

        $newOrder = $request->order;
        $i = 0;
        foreach ($newOrder as $no) {
            Station::find($no)
                ->aerodromes()
                ->updateExistingPivot($aerodrome->id, ['order' => $i]);
            $i++;
        }
        return response(true);
    }

    public function addStation(Request $request, Aerodrome $aerodrome)
    {
        $this->authorize('update', $aerodrome);

        $aerodrome->stations()->attach($request->get('newStation'));

        return response(true);
    }

    public function assignChart(Request $request, Aerodrome $aerodrome)
    {
        $this->authorize('update', $aerodrome);

        $chart = Chart::findOrFail($request->chart);

        $aerodrome->charts()->attach($chart);

        return response(true);
    }

    public function unassignChart(Request $request, Aerodrome $aerodrome)
    {
        $this->authorize('update', $aerodrome);

        $chart = Chart::findOrFail($request->chart);

        $aerodrome->charts()->detach($chart);

        return response(true);
    }

    public function toggleChartfox(Request $request, Aerodrome $aerodrome)
    {
        $this->authorize('update', $aerodrome);

        $aerodrome->update([
            'useChartfox' => !$aerodrome->useChartfox,
        ]);

        return true;
    }

    /**
     * Function to grab related charts for a given aerodrome
     *
     * @param Aerodrome $aerodrome The aerodrome the charts are for
     *
     * @return Collection The related charts
     */
    protected function _getCharts(Aerodrome $aerodrome): Collection
    {
        $aipcharts = [];
        if (Cache::has('org.vatsim-germany.navigation.aerodromes.charts.dfs')) {
            $dfsCharts = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.dfs');
            foreach ($dfsCharts as $dc) {
                if (Str::contains($dc->name, $aerodrome->icao)) {
                    $aipcharts[] = $dc;
                }
            }
        }
        $charts = $aerodrome->charts;
        return $charts->concat($aipcharts);
    }
}
