<?php

namespace App\Http\Controllers\Administration\Tech;

use App\Console\Kernel;
use App\Http\Controllers\Controller;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Events\Dispatcher;
use function app;

class BackendController extends Controller
{
    public function index()
    {
        $this->authorize('tech-access');

        new Kernel(app(), new Dispatcher());
        $schedule = app(Schedule::class);
        $schedule_events = $schedule->events();
        return $this->prepareView('administration.tech.index')->with('schedule_events', $schedule_events);
    }
}
