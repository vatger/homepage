<?php

namespace App\Livewire\Atc;

use App\Libraries\VATSIM\ATCBookingsApi;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\AtcBooking;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class BookPositionTab extends Component
{
    use NotyTrait, SearchTrait;

    public string $station_search = '';

    #[Locked]
    public ?Station $selected_station = null;

    public string $selected_date;

    public string $selected_start_at;

    public string $selected_end_at;

    public bool $selected_voice = true;

    public bool $selected_event = false;

    public bool $selected_training = false;

    protected array $searchable_fields = ['ident', 'name', 'frequency'];

    public function mount(): void
    {
        $this->selected_date = Carbon::now()->format('Y-m-d');
        $this->selected_start_at = Carbon::now()
            ->addHours(0.99)
            ->format('H00');
        $this->selected_end_at = Carbon::now()
            ->addHours(2.99)
            ->format('H00');
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
            'selected_date' => 'required|date_format:Y-m-d|after_or_equal:today|before:+2 month',
            'selected_start_at' => 'required|date_format:Hi',
            'selected_end_at' => 'required|date_format:Hi',
            'selected_voice' => 'required|boolean',
            'selected_event' => 'required|boolean',
            'selected_training' => 'required|boolean',
        ]);
        $this->val = 1;

        $b = new AtcBooking;
        $b->station_id = $validated['selected_station']['id'];
        $b->controller_id = Auth::user()->id;
        $day = Carbon::createFromFormat('Y-m-d', $validated['selected_date']);
        $b->starts_at = $day
            ->copy()
            ->setTimeFromTimeString(substr($validated['selected_start_at'], 0, 2).':'.substr($validated['selected_start_at'], 2, 2));
        $b->ends_at = $day
            ->copy()
            ->setTimeFromTimeString(substr($validated['selected_end_at'], 0, 2).':'.substr($validated['selected_end_at'], 2, 2));
        $b->voice = $validated['selected_voice'];
        $b->event = $validated['selected_event'];
        $b->training = $validated['selected_training'];

        $check = $this->checkBooking($b);
        if ($check) {
            $this->showNoty($check, 'warning');

            return;
        }

        $res = ATCBookingsApi::createAndSaveBooking($b);

        $this->showNoty($res['message'], $res['ok'] ? 'success' : 'warning');

        if ($res['ok']) {
            $this->selected_station = null;
            $this->station_search = '';
        }
    }

    private function checkBooking(AtcBooking $b): ?string
    {
        $allowed_start = Carbon::now()->addHours(0.5);
        if ($b->starts_at->isBefore($allowed_start)) {
            return "You can't book a station this close to the start.";
        }

        $already_controller = DataFeedLibrary::Controller($b->station);
        $allowed_start = Carbon::now()->addHours(2.5);
        if ($already_controller && $b->starts_at->isBefore($allowed_start)) {
            return "You can't book this station. There is someone already connected to this station.";
        }

        return null;
    }
}
