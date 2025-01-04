<?php

namespace App\Livewire\Administration\Nav;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StationListPage extends Component
{
    use PaginationTrait, SearchTrait, SortableTrait;

    protected array $sortable_fields = ['name', 'ident', 'active'];

    protected array $searchable_fields = ['name', 'ident', 'frequency'];

    public string $searchstr = '';

    public function boot(): void
    {
        $this->authorize('navigation.stations.view');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $stations = Station::query();
        $this->sortQueryModifier($stations);
        $this->searchQueryModifier($stations, $this->searchstr);

        return view('pages.admin.stations')->with(['stations' => $stations->get()->paginate()]);
    }
}
