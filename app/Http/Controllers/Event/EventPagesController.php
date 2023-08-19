<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class EventPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function view($eventId)
    {
        $event = EventLibrary::getEvent($eventId);

        return $this->view('pages.event')->with(['user' => Auth::user(), 'event' => $event]);
    }
}
