<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Events\EventRoute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EventroutesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function info(Request $request): View
    {
        return $this->prepareView('homepage.events.eventroutes.infos');
    }

    public function routes(Request $request): View
    {
        $events = EventRoute::where('visible', true)
            ->with('legs.arrival', 'legs.departure')
            ->get();
        return $this->prepareView('homepage.events.eventroutes.routes')->with(['events' => $events]);
    }

    public function view(Request $request, EventRoute $eventRoute)
    {
    }

    public function signupEventRoute(Request $request, EventRoute $eventRoute): RedirectResponse
    {
        foreach ($eventRoute->legs as $leg) {
            $leg->accounts()->detach(Auth::User());
            $leg->accounts()->attach(Auth::User());
        }
        return Redirect::back()->withSuccess('signedup');
    }
}
