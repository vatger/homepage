<?php

namespace App\Livewire\Atc;

use App\Libraries\VATSIM\ATCBookingsApi;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\AtcBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListAtcBookingTab extends Component
{
    use SearchTrait, SortableTrait, PaginationTrait, NotyTrait;

    public string $selected_search = '';
    public string $selected_start_at = '';
    public string $selected_end_at = '';
    public bool $selected_my_bookings = false;
    protected array $searchable_fields = ['station.ident', 'station.name', 'station.frequency'];

    public function mount(): void
    {
        $this->setInitialSortOrder('starts_at', 'asc');
        $this->selected_start_at = Carbon::now()->format('Y-m-d');
        $this->selected_end_at = Carbon::now()
            ->addDays(2)
            ->format('Y-m-d');
    }

    public function boot(): void
    {
        $this->setSortable(['controller_id', 'starts_at']);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if ($this->selected_my_bookings) {
            $bookings_filtered_query = AtcBooking::with('station')->where('starts_at', '>=', Carbon::now()->format('Y-m-d'))->where('controller_id', Auth::id());
        } else {
            $bookings_filtered_query = AtcBooking::with('station')
                ->where('starts_at', '>=', Carbon::parse($this->selected_start_at)->format('Y-m-d'))
                ->where(
                    'ends_at',
                    '<=',
                    Carbon::parse($this->selected_end_at)
                        ->addDay()
                        ->format('Y-m-d'),
                );
            $this->searchQueryModifier($bookings_filtered_query, $this->selected_search);
            $this->sortQueryModifier($bookings_filtered_query);
        }
        return view('components.atcbooking.allbookingstab')->with([
            'filtered_bookings' => $bookings_filtered_query->get()->paginate(),
        ]);
    }

    public function delete(int $id): void
    {
        $b = AtcBooking::findOrFail($id);
        if ($b->controller_id != \Auth::user()->id) {
            abort(401);
        }
        $res = ATCBookingsApi::deleteBooking($b);
        $this->showNoty($res['message'], $res['ok'] ? 'success' : 'warning');
    }
}
