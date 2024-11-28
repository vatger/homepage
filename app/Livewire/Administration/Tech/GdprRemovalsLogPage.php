<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\GdprRemoval;
use App\OpenApi\Models\ApiLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class GdprRemovalsLogPage extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    #[Url]
    public $search;

    protected $sortable_fields = ['started_at', 'completed_at', 'canceled_at', 'user_id'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = GdprRemoval::where('user_id', 'LIKE', $this->search . '%');
        $this->sortQueryModifier($query);
        return view('pages.admin.gdprremovallogs')->with(['logs' => $query->paginate(100)]);
    }
}
