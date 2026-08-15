<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Services\ExternalServiceHealthService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AdministrationPagesController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, ExternalServiceHealthService $services)
    {
        $this->authorize('administration.access');

        return view('pages.admin.landing', ['externalServices' => $services->check()]);
    }
}
