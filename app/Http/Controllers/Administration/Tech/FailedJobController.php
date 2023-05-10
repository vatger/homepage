<?php

namespace App\Http\Controllers\Administration\Tech;

use App\Http\Controllers\Controller;
use App\Models\Tech\FailedJob;
use Illuminate\Http\Request;

class FailedJobController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->authorize('tech-access');

        $failedJobs = FailedJob::all();

        return $this->prepareView('administration.tech.jobs')->with('failedJobs', $failedJobs);
    }
}
