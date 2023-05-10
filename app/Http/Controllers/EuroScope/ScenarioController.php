<?php

namespace App\Http\Controllers\EuroScope;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Libraries\EuroScope\ScenarioLibrary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ScenarioController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Method index
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $scenarios = [];
        $files = Storage::files('euroscope/simsessions');
        foreach ($files as $f) {
            $scenarios[] = [
                'name' => Str::beforeLast(Str::afterLast($f, '/'), '.'),
                'date' => Carbon::createFromTimestamp(Storage::lastModified($f))
                    ->utc()
                    ->format('d.m.Y H:i'),
            ];
        }
        return $this->prepareView('homepage.euroscope.scenarios.index')->with('scenarios', $scenarios);
    }

    /**
     * Display the scenario file
     *
     * @param Request $request
     * @param string $name The name of the scenariofile
     *
     * @return void
     */
    public function show(Request $request, string $name)
    {
        return $this->prepareView('homepage.euroscope.scenarios.show')
            ->with('name', $name)
            ->with('scenario', Storage::get('euroscope/simsessions/' . $name . '.txt'));
    }

    /**
     * Download a given scenario file
     *
     * @param Request $request
     * @param string $name The name of the scenario file
     *
     * @return void
     */
    public function download(Request $request, string $name)
    {
        return Storage::download('euroscope/simsessions/' . $name . '.txt');
    }

    /**
     * Method create
     *
     * @return View
     */
    public function create(): View
    {
        return $this->prepareView('homepage.euroscope.scenarios.create');
    }

    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'icao' => 'required|string',
            'range' => 'required|numeric|min:15|max:500',
            'maxFlights' => 'required|numeric|min:1|max:1000',
            'depArrScale' => 'required|numeric|min:0|max:100',
            'depAltLimit' => 'required|numeric|min:0|max:47000',
            'minSquawk' => 'required|numeric|min:0001|max:7777|lt:maxSquawk',
            'maxSquawk' => 'required|numeric|min:0001|max:7777|gt:minSquawk',
            'initialPseudo' => 'required|string',
            'holdings' => 'nullable|string',
            'runways' => 'nullable|string',
        ]);

        $scl = new ScenarioLibrary($validated);

        $build = $scl->_buildScenario();
        $timestamp = Carbon::now()->utc()->timestamp;
        Storage::put('euroscope/simsessions/' . $scl->getName() . '_' . $timestamp . '.txt', $build);

        return redirect()
            ->route('euroscope.scenarios.index')
            ->withSuccess('Scenario ' . $scl->getName() . ' created!');
    }
}
