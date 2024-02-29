<?php

namespace App\Livewire;

use App\Livewire\Helpers\NotyTrait;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Restricted extends Component
{
    use NotyTrait;

    public string $restriction = '1';

    #[Locked]
    public $restrictions = [];
    #[Locked]
    public $stations = [];
    private $filteredStations = [];

    public function mount()
    {
        $restrictionsDB = Station::select('gcap_class_group')
            ->distinct()
            ->where('gcap_class_group', '!=', '0')
            ->get();

        foreach ($restrictionsDB as $stations) {
            if ($stations->gcap_class_group != '') {
                $this->restrictions[] = (object) [
                    'id' => $stations->gcap_class_group,
                    'desc' => $stations->gcap_class_group == '1' ? 'Tier 1' : $stations->gcap_class_group,
                ];
            }
        }

        $stationss = Station::select('ident', 'name', 'frequency', 'gcap_class_group')
            ->where('active', '=', '1')
            ->get();

        foreach ($stationss as $station) {
            $this->stations[] = (object) [
                'ident' => $station->ident,
                'name' => $station->name,
                'frequency' => $station->frequency,
                'gcap_class_group' => $station->gcap_class_group,
            ];
        }
    }
    #[Layout('layouts.master')]
    public function render()
    {
        $this->filteredStations = array_filter($this->stations, function ($station) {
            return $station->gcap_class_group == $this->restriction;
        });
        return view('pages.restricted')->with(['rests' => $this->restrictions, 'fstations' => $this->filteredStations]);
    }
}
