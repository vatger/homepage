<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

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
