<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MemberListPage extends Component
{
    use PaginationTrait, SearchTrait, SortableTrait;

    // query params
    protected array $queryString = ['membersearch', 'filter_ger', 'sort_by', 'sort_order'];

    public string $membersearch = '';

    public bool $filter_ger = false;

    public bool $filter_active = true;

    public bool $filter_inactive = true;

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
    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $this->authorize('membership.users.view');
        // build sql query
        $query = User::with(['vatsimDetails', 'vatgerDetails']);
        $search_str = strtolower($this->membersearch.'');
        $search_str = trim($search_str);

        $this->searchQueryModifier($query, $search_str);

        if ($this->filter_ger) {
            $query = $query->whereHas('vatsimDetails', function ($query) {
                $query->where('subdivision_code', 'LIKE', 'GER');
            });
        }

        if ($this->filter_active && ! $this->filter_inactive) {
            $query = $query->whereHas('vatgerDetails', function ($query) {
                $query->whereNotNull('active_member_at');
            });
        }

        if ($this->filter_inactive && ! $this->filter_active) {
            $query = $query->whereHas('vatgerDetails', function ($query) {
                $query->whereNull('active_member_at');
            });
        }

        $this->sortQueryModifier($query);

        // further filtering

        return view('pages.admin.members')->with(['filtered_members' => $query->paginate(25)]);
    }
}
