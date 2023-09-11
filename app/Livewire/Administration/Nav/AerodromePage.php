<?php

namespace App\Livewire\Administration\Nav;

use App\Livewire\Helpers\SearchTrait;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AerodromePage extends Component
{
    use SearchTrait;

    #[Locked]
    public Aerodrome $aerodrome;

    protected array $searchable_fields = ['name', 'ident', 'frequency'];
    public string $station_search = '';

    public function boot(): void
    {
        $this->authorize('navigation.aerodromes.view');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $stations = empty($this->station_search) ? null : Station::query();
        $this->searchQueryModifier($stations, $this->station_search);
        return view('pages.admin.aerodrome')->with([
            'aerodrome' => $this->aerodrome,
            'station_search_results' => $stations?->limit(3)->get(),
        ]);
    }

    public function add_station(int $id): void
    {
        $this->authorize('navigation.aerodromes.edit');
        $s = Station::findOrFail($id);
        $s->aerodromes()->attach($this->aerodrome->id);
        $this->station_search = '';
    }

    public function del_station(int $id): void
    {
        $this->authorize('navigation.aerodromes.edit');
        $s = Station::findOrFail($id);
        $s->aerodromes()->detach($this->aerodrome->id);
    }
}
