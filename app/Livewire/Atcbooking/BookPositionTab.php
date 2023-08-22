<?php

namespace App\Livewire\Atcbooking;

use App\Libraries\VATSIM\ATCBookingsApi;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\AtcBooking;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;

class BookPositionTab extends Component
{
    use SearchTrait, NotyTrait;

    public string $station_search = '';
    #[Locked]
    public ?Station $selected_station = null;
    public string $selected_date;
    public string $selected_start_at;
    public string $selected_end_at;
    public bool $selected_voice = true;
    public bool $selected_event = false;
    public bool $selected_training = false;

    public function mount(): void
    {
        $this->selected_date = Carbon::now()->format('Y-m-d');
        $this->selected_start_at = Carbon::now()->format('H:00');
        $this->selected_end_at = Carbon::now()
            ->addHours(2)
            ->format('H:00');
    }

    public function boot(): void
    {
        $this->setSearchable(['ident', 'name', 'frequency']);
    }

    public function render()
    {
        $station_suggestions_query = Station::orderBy('selection', 'desc')->bookable();
        $this->searchQueryModifier($station_suggestions_query, $this->station_search);

        return view('components.atcbooking.bookpositiontab')->with([
            'station_suggestions' => $station_suggestions_query->limit(5)->get(),
        ]);
    }

    public function station_select(int $id): void
    {
        if ($id == -1) {
            $this->selected_station = null;
            return;
        }
        $s = Station::findOrFail($id);
        $s->increment('selection');
        $this->selected_station = $s;
    }

    public int $val = 0;

    public function book(): void
    {
        $this->val = 2;
        $validated = $this->validate([
            'selected_station' => 'required',
            'selected_date' => 'required|date',
            'selected_start_at' => 'required',
            'selected_end_at' => 'required',
            'selected_voice' => 'required',
            'selected_event' => 'required',
            'selected_training' => 'required',
        ]);
        $this->val = 1;

        $b = new AtcBooking();
        $b->station_id = $validated['selected_station']['id'];
        $b->controller_id = Auth::user()->id;
        $day = Carbon::createFromFormat('Y-m-d', $validated['selected_date']);
        $b->starts_at = $day->copy()->setTimeFromTimeString($validated['selected_start_at']);
        $b->ends_at = $day->copy()->setTimeFromTimeString($validated['selected_end_at']);

        $res = ATCBookingsApi::createAndSaveBooking($b);

        $this->showNoty($res['message'], $res['ok'] ? 'success' : 'warning');
    }
}
