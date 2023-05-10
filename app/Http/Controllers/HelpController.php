<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Return faq view
     *
     * @return Factory|View|Application
     */
    public function faq(): Factory|View|Application
    {
        return view('homepage.general.help.faq');
    }
}
