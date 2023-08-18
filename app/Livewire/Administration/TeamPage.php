<?php

namespace App\Livewire\Administration;

use App\Models\Groups\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class TeamPage extends Component
{
    public Team $team;

    public function boot()
    {
        $this->authorize('membership.teams.view');
    }

    #[Layout('layouts.admin-master')]
    public function render()
    {
        return view('pages.admin.team')->with(['team' => $this->team, 'permissions' => Permission::all()]);
    }
}
