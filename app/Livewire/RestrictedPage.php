<?php

namespace App\Livewire;

use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RestrictedPage extends Component
{
    public string $restriction = '1';

    #[Locked]
    public $restrictions = [];


    public function mount(): void
    {
        $restrictionsDB = Station::select('gcap_class_group')
            ->distinct()
            ->where('gcap_class_group', '!=', '0')
            ->get();

        foreach ($restrictionsDB as $stations) {
            if ($stations->gcap_class_group != '') {
                $this->restrictions[] = (object)[
                    'id' => $stations->gcap_class_group,
                    'desc' => $stations->gcap_class_group == '1' ? 'Tier 1' : $stations->gcap_class_group,
                ];
            }
        }
    }

    #[Layout('layouts.master')]
    public function render()
    {
        $stations = Station::where('active', true)
            ->where('gcap_class_group', 'LIKE', $this->restriction)
            ->get();
        return view('pages.restricted')->with(['rests' => $this->restrictions, 'stations' => $stations]);
    }
}
