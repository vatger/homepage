<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Members extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    // query params
    protected $queryString = ['membersearch', 'filter_ger', 'sort_by', 'sort_order'];
    public $membersearch;
    public $filter_ger;

    public function boot(): void
    {
        $this->setSortable(['id', 'lastname', 'created_at']);
        $this->setSearchable(['id', 'email']);
        $this->setCustomNameFiltering();
    }

    public function mount(): void
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    #[Layout('layouts.admin-master')]
    public function render()
    {
        // build sql query
        $userquery = User::with(['vatsimDetails', 'vatgerDetails']);
        $search_str = strtolower($this->membersearch . '');

        $this->searchQueryModifier($userquery, $search_str);

        if ($this->filter_ger) {
            $userquery = $userquery->whereHas('vatsimDetails', function ($query) {
                $query->where('subdivision_code', 'LIKE', 'GER');
            });
        }

        $this->sortQueryModifier($userquery);

        // further filtering
        $filtered_members = collect($userquery->get());
        $this->searchCollectionModifier($filtered_members, $search_str);

        return view('pages.admin.members')->with(['filtered_members' => $filtered_members->paginate(5)]);
    }
}
