<?php

namespace App\Livewire\Atcbooking;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\AtcBooking;
use Livewire\Component;

class ListAtcBookingTab extends Component
{
    use SortableTrait, PaginationTrait;

    public function mount(): void
    {
        $this->setInitialSortOrder('starts_at', 'asc');
    }

    public function boot(): void
    {
        $this->setSortable(['controller_id', 'station_id', 'starts_at']);
    }

    public function render()
    {
        $bookings_filtered_query = AtcBooking::with('station');
        $this->sortQueryModifier($bookings_filtered_query);

        return view('components.atcbooking.allbookingstab')->with([
            'filtered_bookings' => $bookings_filtered_query->get()->paginate(),
        ]);
    }
}
