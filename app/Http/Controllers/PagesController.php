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

    public function terms()
    {
        $gdpr = Storage::get('policies/gdpr.html');
        $imprint = Storage::get('policies/imprint.html');
        $gdpr_date = Carbon::createFromTimestamp(Storage::lastModified('policies/gdpr.html'));
        $imprint_date = Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
        return view('pages.terms')->with([
            'gdpr' => $gdpr,
            'imprint' => $imprint,
            'gdpr_date' => $gdpr_date,
            'imprint_date' => $imprint_date,
        ]);
    }
}
