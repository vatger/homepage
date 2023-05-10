<?php

namespace App\Http\Controllers\EuroScope;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SectorController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return $this->prepareView('homepage.euroscope.sector.index');
    }

    public function download(Request $request)
    {
        if (!Storage::exists('euroscope/sectorfiles/build/combined.zip')) {
            Artisan::call('euroscope:sectorcombine');
        }

        return Storage::download('euroscope/sectorfiles/build/combined.zip');
    }
}
