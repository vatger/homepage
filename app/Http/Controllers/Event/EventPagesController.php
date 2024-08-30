<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;

class EventPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function view(int $id)
    {
        $event = EventLibrary::getEvent($id);
        if (!$event) abort(404);
        return view('pages.event')->with(['event' => $event]);
    }
}
