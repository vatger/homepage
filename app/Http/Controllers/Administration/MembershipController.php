<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    use AuthorizesRequests;

    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->authorize('administration.access');
        return view('pages.admin.landing');
    }
}
