<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function gdpr(): View
    {
        return view('pages.gdpr');
    }

    public function imprint(): View
    {
        return view('pages.imprint');
    }
}
