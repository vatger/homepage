<?php

namespace App\Http\Controllers\Pilot\Aerodrome;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Aerodrome;
use App\Models\Regionalgroup_remove\FlightInformationRegion;
use App\Models\Regionalgroup_remove\Regionalgroup;
use CobaltGrid\VatsimStandStatus\Exceptions\CoordinateOutOfBoundsException;
use CobaltGrid\VatsimStandStatus\Exceptions\NoStandDataException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Navigation\StandStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AerodromeController extends Controller
{
    /**
     * Returns the aerodrome-overview page
     *
     * @return Factory|View|Application
     */
    public function viewAerodromes(): Factory|View|Application
    {
        return view('homepage.pilots.aerodromes.aerodrome-list');
    }

    public function getAllAerodromes(Request $request, $includeInternational = false)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        if ($includeInternational) {
            return Aerodrome::where('active', 1)->get();
        } else {
            return Aerodrome::isDe()
                ->where('active', 1)
                ->get();
        }
    }

    public function getAerodromesSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }

        if ($request->get('search_fir') != null) {
            $fir = FlightInformationRegion::with(['regionalgroups.aerodromes'])->find($request->get('search_fir'));

            $res = [];
            foreach ($fir->regionalgroups as $rg) {
                foreach ($rg->aerodromes as $ad) {
                    $res[] = $ad;
                }
            }

            return $res;
        } else {
            return Aerodrome::where('active', 1)
                ->where('icao', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orWhere('iata', 'LIKE', '%' . $request->get('search_param') . '%')
                ->orWhere('name', 'LIKE', '%' . $request->get('search_param') . '%')
                ->isDe()
                ->get();
        }
    }

    /**
     * Returns page containing information on a single aerodrome
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function viewAerodrome(Request $request): View|Factory|RedirectResponse|Application
    {
        $aerodrome = Aerodrome::icao($request->route('icao'))->first();

        if ($aerodrome == null) {
            return redirect()
                ->route('pilots.aerodromes.viewall')
                ->withErrors(['Requested Airport not found.']);
        }

        if ($aerodrome->country != 'DE') {
            return redirect()
                ->route('pilots.aerodromes.viewall')
                ->withErrors(['Requested Airport not in Germany.']);
        }

        return view('homepage.pilots.aerodromes.aerodrome')->with(['aerodrome' => $aerodrome]);
    }

    /**
     * Returns page containing chart information on a single aerodrome
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function viewAerodromeCharts(Request $request): View|Factory|RedirectResponse|Application
    {
        $aerodrome = Aerodrome::icao($request->route('icao'))->first();

        if ($aerodrome == null) {
            return redirect()
                ->route('pilots.aerodromes.viewall')
                ->withErrors(['Requested Airport not found.']);
        }

        if ($aerodrome->country != 'DE') {
            return redirect()
                ->route('pilots.aerodromes.viewall')
                ->withErrors(['Requested Airport not in Germany.']);
        }

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

        return view('homepage.pilots.aerodromes.charts')->with(['aerodrome' => $aerodrome, 'charts' => $charts]);
    }

    /**
     * @throws NoStandDataException
     * @throws CoordinateOutOfBoundsException
     */
    public function getStandStatus(Request $request)
    {
        if ($request->ajax()) {
            $aerodrome = Aerodrome::icao($request->route('icao'))->first();

            if ($aerodrome === null) {
                return [];
            }

            $standFilePath = storage_path('app') . '/navigation/stands/' . strtolower($aerodrome->icao) . '.csv';

            if (File::exists($standFilePath)) {
                $stands = new StandStatus($aerodrome->latitude, $aerodrome->longitude);
                $stands
                    ->setMaxStandDistance(0.02)
                    ->setMaxDistanceFromAirport(5)
                    ->setMaxAircraftAltitude($aerodrome->elevation + 300);
                $stands->loadStandDataFromCSV($standFilePath)->parseData();

                if ($stands) {
                    $result = [];
                    foreach ($stands->stands() as $stand) {
                        $result[] = (object) [
                            'id' => $stand->getName(),
                            'latitude' => $stand->latitude,
                            'longitude' => $stand->longitude,
                            'occupier' => $stand->occupier?->callsign,
                        ];
                    }
                    return $result;
                }
            } else {
                return [];
            }
        }
        abort(403);
    }

    /**
     * Function to grab related charts for a given aerodrome
     *
     * @param Aerodrome $aerodrome The aerodrome the charts are for
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
