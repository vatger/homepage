<?php

namespace App\Livewire\Atcbooking;

use App\Libraries\VATSIM\ATCBookingsApi;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\AtcBooking;
use Livewire\Component;

class ListAtcBookingTab extends Component
{
    use SortableTrait, PaginationTrait, NotyTrait;

    public function mount(): void
    {
        $this->setInitialSortOrder('starts_at', 'asc');
    }

    public function boot(): void
    {
        $this->setSortable(['controller_id', 'starts_at']);
    }

    public function render()
    {
        $bookings_filtered_query = AtcBooking::with('station');
        $this->sortQueryModifier($bookings_filtered_query);

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
