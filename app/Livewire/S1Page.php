<?php

namespace App\Livewire;

use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Component;

class S1Page extends Component
{
    #[Layout('layouts.master')]
    public function render()
    {
        $stations = Station::where('s1_twr', true)->get();
        return view('pages.s1')->with(['s1stations' => $stations]);
    }
}
