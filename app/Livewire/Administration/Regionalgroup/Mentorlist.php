<?php

namespace App\Livewire\Administration\Regionalgroup;

use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\PaginationTrait;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Mentorlist extends Component
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
        $userquery = $this->rg->mentors();

        return view('administration.regionalgroup.partials.mentorlist_lw')->with([
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
        $this->rg->mentors()->detach($user->id);
        $this->rg->mentors()->attach($user->id);
        $this->showNoty('Eventler hinzugefügt!');
        $this->membersearch = '';
    }

    public function kick(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->mentors()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->mentors()->detach($user->id);
        $this->showNoty('Eventler entfernt!');
        $this->membersearch = '';
    }

    public function toggle_chief(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->mentors()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->mentors()->updateExistingPivot($user->id, ['chief' => !$user->pivot->chief]);
        $this->showNoty('Mentor Status geändert!');
    }

    public function toggle_deputy(int $user_id): void
    {
        $this->authorize('update', $this->rg);
        $user = $this->rg->mentors()->find($user_id);
        if (empty($user)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        $this->rg->mentors()->updateExistingPivot($user->id, ['senior' => !$user->pivot->senior]);
        $this->showNoty('Mentor Status geändert!');
    }
}
