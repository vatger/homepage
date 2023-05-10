<?php

namespace App\Http\Controllers\Administration\Event;

use App\Http\Controllers\Controller;
use Events\EventRoute;

class CPTGeneratorController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->prepareView('administration.event.cptgen.index')->with('routes', EventRoute::all());
    }
}
