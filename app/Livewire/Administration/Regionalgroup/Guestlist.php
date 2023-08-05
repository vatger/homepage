<?php

namespace App\Livewire\Administration\Regionalgroup;

use App\Livewire\Helpers\ModalTrait;
use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Guestlist extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait, ModalTrait, NotyTrait, AuthorizesRequests;

    // query params
    protected $queryString = ['membersearch', 'sort_by', 'sort_order'];
    public $membersearch;
    // component params
    public $rg_id;
    public $delete_account_id = null;
    public $details_account_id = null;

    private Regionalgroup $rg;
    private ?User $delete_account;
    private ?User $details_account;

    public function boot()
    {
        $this->setSortable(['id', 'lastname', 'pivot_created_at']);
        $this->setSearchable(['user_id', 'email']);
        $this->setCustomNameFiltering();
        $this->setModalIds(['del_modal_guest', 'view_modal_guest']);
    }

    public function mount()
    {
        $this->setInitialSortOrder('id', 'asc');
    }

    public function booted()
    {
        $this->rg = Regionalgroup::where('id', $this->rg_id)->firstOrFail();

        $this->authorize('view', $this->rg);
    }

    public function render(): View
    {
        // build sql query
        $userquery = $this->rg->guests()->with('userData');
        $search_str = strtolower($this->membersearch . '');

        $this->searchQueryModifier($userquery, $search_str);

        $this->sortQueryModifier($userquery);

        // further filtering
        $filtered_members = collect($userquery->get());
        $this->searchCollectionModifier($filtered_members, $search_str);

        $this->delete_account = $this->delete_account_id
            ? $this->rg
                ->guests()
                ->where('user_id', $this->delete_account_id)
                ->firstOrFail()
            : null;
        $this->details_account = $this->details_account_id
            ? $this->rg
                ->guests()
                ->where('user_id', $this->details_account_id)
                ->firstOrFail()
            : null;

        return view('administration.regionalgroup.partials.guestlist_lw')->with([
            'regionalgroup' => $this->rg,
            'delete_account' => $this->delete_account,
            'details_account' => $this->details_account,
            'filtered_members' => $filtered_members->paginate(),
        ]);
    }

    public function delete_member(?int $account_id, bool $confirm = false)
    {
        $this->authorize('update', $this->rg);
        if ($account_id == null) {
            $this->closeModal('del_modal_guest');
            $this->delete_account_id = null;
        } elseif (!$confirm) {
            $this->delete_account_id = $account_id;
            $this->openModal('del_modal_guest');
        } elseif ($this->delete_account_id == $account_id) {
            // remove from RG
            $this->closeModal('del_modal_guest');
            $this->showNoty('Gast aus der RG entfernt.');
        }
        $this->closeModal('view_modal_guest');
    }

    public function view_member(?int $account_id)
    {
        if ($account_id == null) {
            $this->closeModal('view_modal_guest');
            $this->details_account_id = null;
        } else {
            $this->details_account_id = $account_id;
            $this->openModal('view_modal_guest');
        }
        $this->closeModal('del_modal_guest');
    }
}
