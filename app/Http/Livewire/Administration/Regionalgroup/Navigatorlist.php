<?php

namespace App\Http\Livewire\Administration\Regionalgroup;

use App\Http\Livewire\Helpers\NotyTrait;
use App\Http\Livewire\Helpers\PaginationTrait;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Navigatorlist extends Component
{
    use NotyTrait, AuthorizesRequests, PaginationTrait;

    // query params
    public $membersearch;
    // component params
    public $rg_id;

    private Regionalgroup $rg;

    public function booted()
    {
        $this->rg = Regionalgroup::where('id', $this->rg_id)->firstOrFail();
        $this->authorize('view', $this->rg);
    }

    public function render(): View
    {
        // build sql query
        $userquery = $this->rg->navigators();

        return view('administration.regionalgroup.partials.navigatorlist_lw')->with([
            'members' => $userquery->paginate(),
        ]);
    }

    public function add(): void
    {
        $this->authorize('update', $this->rg);
        $user = User::query()->find($this->membersearch);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->navigators()->detach($user->id);
        $this->rg->navigators()->attach($user->id);
        $this->showNoty('Eventler hinzugefügt!');
        $this->membersearch = '';
    }

    public function kick(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->navigators()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->navigators()->detach($user->id);
        $this->showNoty('Eventler entfernt!');
        $this->membersearch = '';
    }

    public function toggle_chief(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->navigators()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->navigators()->updateExistingPivot($user->id, ['chief' => !$user->pivot->chief]);
        $this->showNoty('Eventler Status geändert!');
    }

    public function toggle_deputy(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->navigators()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->navigators()->updateExistingPivot($user->id, ['deputy' => !$user->pivot->deputy]);
        $this->showNoty('Eventler Status geändert!');
    }
}
