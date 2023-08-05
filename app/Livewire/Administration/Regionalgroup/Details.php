<?php

namespace App\Livewire\Administration\Regionalgroup;

use App\Livewire\Helpers\NotyTrait;
use App\Models\Membership\User\User;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class Details extends Component
{
    use NotyTrait, AuthorizesRequests;

    public $chief_id;
    public $deputy_id;
    // component params
    public $rg_id;

    private Regionalgroup $regionalgroup;

    public function booted()
    {
        $this->regionalgroup = Regionalgroup::where('id', $this->rg_id)->firstOrFail();
        $this->authorize('view', $this->regionalgroup);
    }

    public function render(): View
    {
        return view('administration.regionalgroup.partials.regionalgroup_detail_lw')->with([
            'regionalgroup' => $this->regionalgroup,
        ]);
    }

    public function make_chief(): void
    {
        $this->authorize('update', $this->regionalgroup);
        $newChief = User::query()->find(123);
        if (empty($newChief)) {
            $this->showNoty('User nicht gefunden!', 'error');
            return;
        }
        if (!$this->regionalgroup->members->contains($newChief)) {
            $this->showNoty('User nicht Mitglied der RG!', 'warning');
            return;
        }
        if ($this->regionalgroup->deputy_id == $newChief->id) {
            $this->regionalgroup->deputy_id = null;
        }
        $this->regionalgroup->chief_id = $newChief->id;
        $this->regionalgroup->save();
    }
}
