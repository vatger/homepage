<?php

namespace App\Http\Controllers\Administration\Tech;

use App\Http\Controllers\Controller;

class ApiLogController extends Controller
{
    public function index()
    {
        $this->authorize('tech-access');
        return view('administration.tech.apilog');
    }
}
