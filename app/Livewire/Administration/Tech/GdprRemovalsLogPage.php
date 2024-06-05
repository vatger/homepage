<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User\GdprRemoval;
use Livewire\Attributes\Layout;
use Livewire\Component;

class GdprRemovalsLogPage extends Component
{
    use PaginationTrait, SortableTrait;


    protected $sortable_fields = ['started_at', 'completed_at', 'user_id'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = GdprRemoval::query();
        $this->sortQueryModifier($query);
        return view('pages.admin.gdprremovallogs')->with(['logs' => $query->paginate()]);
    }
}
