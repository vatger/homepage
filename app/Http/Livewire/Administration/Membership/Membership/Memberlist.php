<?php

namespace App\Http\Livewire\Administration\Membership\Membership;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;
use App\Http\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User\User;
use Illuminate\View\View;
use Livewire\Component;

class Memberlist extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    // query params
    protected $queryString = ['membersearch', 'filter_ger', 'sort_by', 'sort_order'];
    public $membersearch;
    public $filter_ger;

    public function boot()
    {
        $this->setSortable(['id', 'lastname', 'created_at']);
        $this->setSearchable(['id', 'email']);
        $this->setCustomNameFiltering();
    }

    public function mount()
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    public function render(): View
    {
        // build sql query
        $userquery = User::with('userData');
        $search_str = strtolower($this->membersearch . '');

        $this->searchQueryModifier($userquery, $search_str);

        if ($this->filter_ger) {
            $userquery = $userquery->whereHas('userData', function ($query) {
                $query->where('subdivision_code', 'LIKE', 'GER');
            });
        }

        $this->sortQueryModifier($userquery);

        // further filtering
        $filtered_members = collect($userquery->get());
        $this->searchCollectionModifier($filtered_members, $search_str);

        return view('administration.membership.membership.partials.memberlist_lw')->with(['filtered_members' => $filtered_members->paginate(5)]);
    }
}
