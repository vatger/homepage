<?php

namespace App\Livewire\Atc;

use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RestrictedPage extends Component
{
    use SortableTrait, SearchTrait;

    public string $restriction = '1';

    #[Locked]
    public $restrictions = [];

    public string $search = '';

    private array $searchable_fields = ['ident', 'name', 'frequency'];
    private array $sortable_fields = ['ident', 'name', 'frequency'];


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
            ->where('gcap_class_group', 'LIKE', $this->restriction);

        $this->searchQueryModifier($stations, $this->search);
        $this->sortQueryModifier($stations);

        return view('pages.restricted')->with(['rests' => $this->restrictions, 'stations' => $stations->get()]);
    }
}
