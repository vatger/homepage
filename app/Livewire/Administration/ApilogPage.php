<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\OpenApi\Models\ApiLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class ApilogPage extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    #[Url]
    public $search;

    protected $sortable_fields = ['id', 'created_at'];

    public function mount()
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        // build sql query
        $apilogquery = ApiLog::query();
        $search_str = strtolower($this->search . '');
        $this->searchQueryModifier($apilogquery, $search_str);
        $this->sortQueryModifier($apilogquery);
        return view('pages.admin.apilog')->with(['logs' => $apilogquery->paginate()]);
    }

    public function view_log($log_id)
    {
        return;
    }
}
