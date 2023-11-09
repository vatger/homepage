<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return view('pages.banned')->with('ban', Auth::user()?->current_ban);
    }
}
