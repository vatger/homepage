<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use VatsimData\Datafeed;

class HomeController extends Controller
{
    public function home()
    {
        $count = 42;
        return Inertia::render('(public)/(landing)/page', [
            'len_controllers' => Inertia::defer(fn () => $count),
            'len_controllers_ger' => Inertia::defer(fn () => $count),
            'len_pilots' => Inertia::defer(fn () => $count),
            'len_pilots_ger' => Inertia::defer(fn () => $count),
            'last_update' => Inertia::defer(fn () => strval($count)),
        ]);
    }
}
