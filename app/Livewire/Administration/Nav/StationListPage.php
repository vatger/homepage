<?php

namespace App\Livewire\Administration\Nav;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Navigation\Aerodrome;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StationListPage extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    protected array $sortable_fields = ['name', 'icao', 'active'];
    protected array $searchable_fields = ['name', 'icao', 'iata'];

    public string $searchstr = '';

    public function boot(): void
    {
        $this->authorize('navigation.');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $aerodromes = Aerodrome::query();
        $this->sortQueryModifier($aerodromes);
        $this->searchQueryModifier($aerodromes, $this->searchstr);
        return view('pages.admin.aerodromes')->with(['aerodromes' => $aerodromes->get()->paginate()]);
    }
}
