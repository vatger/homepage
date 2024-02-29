<?php

namespace App\Livewire;

use App\Livewire\Helpers\NotyTrait;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class S1 extends Component
{
    use NotyTrait;

    #[Locked]
    public $stations = [];

    public function mount()
    {
        $stationss = Station::select('ident', 'name', 'frequency')
            ->where('s1_twr', '=', 'true')
            ->get();

        foreach ($stationss as $station) {
            $this->stations[] = (object) [
                'ident' => $station->ident,
                'name' => $station->name,
                'frequency' => $station->fixed_frequency,
            ];
        }
    }
    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.s1')->with(['s1stations' => $this->stations]);
    }
}
