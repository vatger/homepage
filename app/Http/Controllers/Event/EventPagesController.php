<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Libraries\VATSIM\EventLibrary;
use Illuminate\Support\Facades\Auth;

class EventPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function view(int $id)
    {
        $event = EventLibrary::getEvent($id);
        return view('pages.event')->with(['user' => Auth::user(), 'event' => $event]);
    }
}
