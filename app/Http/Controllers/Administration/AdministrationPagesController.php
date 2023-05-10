<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministrationPagesController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->authorize('administration-access');

        return $this->prepareView('administration.dashboard');
    }
}
