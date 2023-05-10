<?php

namespace App\Http\Controllers\Administration\Tech;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TechController extends Controller
{
    public function management()
    {
        $this->authorize('tech-access');

        return view('administration.tech.management');
    }
}
