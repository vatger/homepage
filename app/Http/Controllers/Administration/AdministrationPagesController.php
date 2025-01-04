<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AdministrationPagesController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->authorize('administration.access');

        return view('pages.admin.landing');
    }
}
