<?php

namespace App\Http\Controllers;

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

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function satzung()
    {
        return redirect(Storage::url('public/policies/satzung.pdf'));
    }
}
