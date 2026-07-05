<?php

namespace App\Livewire;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Fir;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class AerodromeListPage extends Component
{
    use PaginationTrait, SearchTrait;

    #[Url]
    public string $search = '';

    public int $selected_fir = -1;

    protected array $searchable_fields = ['icao', 'name', 'iata'];

    #[Layout('layouts.master')]
    public function render()
    {
        $aerodromes = Aerodrome::query();
        $this->searchQueryModifier($aerodromes, $this->search);
        $aerodromes->orderBy('selection', direction: 'desc');

        return view('pages.aerodromes')->with([
            'aerodromes' => $aerodromes->get()->paginate()->onEachSide(0),
            'firs' => Fir::all(),
        ]);
    }

    public function aerodrome_select(int $id)
    {
        $a = Aerodrome::findOrFail($id);
        $a->increment('selection');
        $this->redirect(route('pilots.aerodromes.view', ['icao' => $a->icao]));
    }

    public function fir_select(int $id): void
    {
        $this->selected_fir = $id;
    }
}
