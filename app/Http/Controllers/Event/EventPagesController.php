<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

class EventPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function view(Request $request, $eventId)
    {
        $event = EventLibrary::getEvent($eventId);

        return $this->prepareView('homepage.events.view')->with(['event' => $event]);
    }
}
