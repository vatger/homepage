<?php

namespace App\Http\Livewire\Administration\Tech;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;
use App\Http\Livewire\Helpers\SortableTrait;
use App\OpenApi\Models\ApiLog;
use Illuminate\View\View;
use Livewire\Component;

class Apilogtable extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    // query params
    protected $queryString = ['search'];
    public $search;

    public function boot()
    {
        $this->setSortable(['id', 'created_at']);
    }

    public function mount()
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    public function render(): View
    {
        // build sql query
        $apilogquery = ApiLog::query();
        $search_str = strtolower($this->search . '');

        $this->searchQueryModifier($apilogquery, $search_str);

        $this->sortQueryModifier($apilogquery);

        return view('administration.tech.partials.apilogtable_lw')->with(['filtered_logs' => $apilogquery->paginate()]);
    }

    public function view_log($log_id)
    {
        return;
    }
}
