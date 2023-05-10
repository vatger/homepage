<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Libraries\EuroScope\ScenarioLibrary;
use Carbon\Carbon;

Route::prefix('euroscope')->group(function () {
    Route::post('scenario', function (Request $request) {
        if ($request->hasHeader('rgbremen-authentication')) {
            if ($request->header('rgbremen-authentication') == config('api-access.rgbremen-api-token')) {
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
                ]);

                $scl = new ScenarioLibrary($validated);

                $build = $scl->_buildScenario();
                $timestamp = Carbon::now()->utc()->timestamp;
                Storage::put('euroscope/simsessions/' . $scl->getName() . '_' . $timestamp . '.txt', $build);

                return response($scl->getName() . '_' . $timestamp . '.txt');
            } else {
                abort(403);
            }
        }
        abort(401);
    });
});
