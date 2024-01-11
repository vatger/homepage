<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MemberListPage extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait;

    // query params
    protected array $queryString = ['membersearch', 'filter_ger', 'sort_by', 'sort_order'];
    public string $membersearch = '';
    public bool $filter_ger = false;

    public function mount(): void
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    public function boot(): void
    {
        $this->authorize('membership.users.view');
        $this->setSortable(['id', 'lastname', 'created_at']);
        $this->setSearchable(['id', 'email']);
        $this->setCustomNameFiltering();
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('membership.users.view');
        // build sql query
        $query = User::with(['vatsimDetails', 'vatgerDetails']);
        $search_str = strtolower($this->membersearch . '');
        $search_str = trim($search_str);

        $this->searchQueryModifier($query, $search_str);

        if ($this->filter_ger) {
            $query = $query->whereHas('vatsimDetails', function ($query) {
                $query->where('subdivision_code', 'LIKE', 'GER');
            });
        }

        $this->sortQueryModifier($query);

        // further filtering
        $filtered_members = collect($query->get());
        $this->searchCollectionModifier($filtered_members, $search_str);

        return view('pages.admin.members')->with(['filtered_members' => $filtered_members->paginate(25)]);
    }
}
