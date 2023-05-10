<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FirstStepsController extends Controller
{
    /**
     * Returns "getting-started/atc" page
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(): View|Factory|RedirectResponse|Application
    {
        return view('homepage.general.firststeps.atc.index');
    }

    public function index1(): View|Factory|RedirectResponse|Application
    {
        return view('homepage.general.firststeps.pilot.index');
    }
}
