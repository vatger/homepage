<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\GdprRemoval;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class GdprRemovalsLogPage extends Component
{
    use PaginationTrait, SearchTrait, SortableTrait;

    #[Url]
    public $search;

    public bool $filter_running = true;

    public bool $filter_completed = true;

    public bool $filter_canceled = true;

    protected $sortable_fields = ['started_at', 'completed_at', 'canceled_at', 'user_id'];

    public function mount(): void
    {
        $this->setInitialSortOrder('started_at', 'desc');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = GdprRemoval::where('user_id', 'LIKE', $this->search.'%');

        $query->where(function ($q) {
            if ($this->filter_running) {
                $q->orWhere(function ($q) {
                    $q->whereNull('completed_at')
                        ->whereNull('canceled_at');
                });
            }
            if ($this->filter_completed) {
                $q->orWhereNotNull('completed_at');
            }
            if ($this->filter_canceled) {
                $q->orWhereNotNull('canceled_at');
            }
        });

        $this->sortQueryModifier($query);

        return view('pages.admin.gdprremovallogs')->with(['logs' => $query->paginate(100)]);
    }
}
