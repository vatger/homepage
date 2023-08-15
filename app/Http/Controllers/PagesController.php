<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    /**
     * Returns the vACC Information view
     *
     * @return Factory|View|Application
     */
    public function getStarted(): Factory|View|Application
    {
        // MediaWikiLibrary::load();

        return view('homepage.general.firststeps.getstarted');
    }
}
