<?php

namespace App\Livewire\Atc;

use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Component;

class S1StationsPage extends Component
{
    use SearchTrait, SortableTrait;

    public string $search = '';

    private array $searchable_fields = ['ident', 'name', 'frequency'];

    private array $sortable_fields = ['ident', 'name', 'frequency'];

    #[Layout('layouts.master')]
    public function render()
    {
        $stations = Station::where('s1_station', true);
        $this->searchQueryModifier($stations, $this->search);
        $this->sortQueryModifier($stations);

        return view('pages.s1-theory')->with(['s1stations' => $stations->get()]);
    }
}
