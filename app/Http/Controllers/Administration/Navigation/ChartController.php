<?php

namespace App\Http\Controllers\Administration\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Navigation\Chart;
use DOMDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChartController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return View The view that will be displayed
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Chart::class);

        $charts = Chart::paginate(15);

        // Those are not needed here
        // if(Cache::has('org.vatsim-germany.navigation.aerodromes.charts.dfs')){
        //     $dfsCharts = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.dfs');
        //     $charts = $charts->concat($dfsCharts);
        // }

        return $this->prepareView('administration.navigation.charts.index')->with('charts', $charts);
    }

    public function getChartsPaginated(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported.');
        }

        $this->authorize('viewAny', Chart::class);

        $charts = Chart::paginate(15);

        // if(Cache::has('org.vatsim-germany.navigation.aerodromes.charts.dfs')){
        //     $dfsCharts = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.dfs');
        //     $charts = $charts->concat($dfsCharts);
        // }

        return $charts;
    }

    public function getChartsSearch(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Method not supported');
        }
        $this->authorize('viewAny', Chart::class);

        return Chart::where('name', 'LIKE', '%' . $request->get('search_param') . '%')->get();
    }

    public function show(Request $request, Chart|string $chart): View|RedirectResponse
    {
        $this->authorize('viewAny', Chart::class);

        if ($c = Chart::find($chart)) {
            return $this->prepareView('administration.navigation.charts.show')
                ->with('chart', $c)
                ->with('is_dfs', false);
        } else {
            $dfsCharts = Cache::get('org.vatsim-germany.navigation.aerodromes.charts.dfs');
            $dc = null;
            foreach ($dfsCharts as $c) {
                if ($c->id === $chart) {
                    $dc = $c;
                    break;
                }
            }

            if ($dc === null) {
                return redirect()
                    ->back()
                    ->withErrors('Chart ' . $chart . ' not found');
            } else {
                // THIS IS ONLY FOR ADMINISTRATION
                // WE WILL NEVER DO THIS AT THE GENERAL FRONT END
                // WE SHALL NEVER DO THIS FOR PUBLIC PAGES
                $doc = new DOMDocument();
                @$doc->loadHTML(file_get_contents($dc->link));
                $images = $doc->getElementsByTagName('img');

                $imgSource = '';
                foreach ($images as $i) {
                    if ($i->getAttribute('class') == 'pageImage') {
                        $imgSource = $i->getAttribute('src');
                        break;
                    }
                }

                return $this->prepareView('administration.navigation.charts.show')
                    ->with('chart', $dc)
                    ->with('imgSource', $imgSource)
                    ->with('is_dfs', true);
            }
        }
    }

    public function create(Request $request)
    {
        $this->authorize('create', Chart::class);

        return $this->prepareView('administration.navigation.charts.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Chart::class);

        $validated = $request->validate([
            'name' => 'required|string',
            'href' => 'required|string',
            'published' => 'nullable',
            'public_available' => 'nullable',
            'airac' => 'required|numeric',
            'type' => 'required|in:aoi,afc,agc,apc,sid,star,iac',
            'fri' => 'required|in:ifr,vfr',
        ]);

        $validated['published'] = $request->has('published') && $request->published == 'on';
        $validated['public_available'] = $request->has('public_available') && $request->public_available == 'on';

        $chart = new Chart();
        $chart->name = $validated['name'];
        $chart->href = $validated['href'];
        $chart->published = $validated['published'];
        $chart->public_available = $validated['public_available'];
        $chart->airac = intval($validated['airac']);
        $chart->type = $validated['type'];
        $chart->fri = $validated['fri'];

        $saved = $chart->save();

        if ($request->ajax() && $saved) {
            return json_encode($chart);
        } elseif ($request->ajax() && !$saved) {
            return false;
        } elseif (!$request->ajax() && $saved) {
            return redirect()
                ->back()
                ->withSuccess(__('profile.profile.notifications.settings-saved-successfully'));
        } else {
            return redirect()
                ->back()
                ->withErrors('Could not create chart.');
        }
    }

    public function destroy(Request $request, Chart $chart)
    {
        $this->authorize('delete', $chart);

        $chart->aerodromes()->detach();

        if ($chart->delete()) {
            return redirect()
                ->route('administration.navigation.charts')
                ->withSuccess('Chart has been removed!');
        } else {
            return redirect()
                ->back()
                ->withErrors('Could not remove chart.');
        }
    }
}
