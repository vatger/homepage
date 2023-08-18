<?php

namespace App\Livewire\Administration;

use App\Models\Groups\Team;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TeamPage extends Component
{
    public Team $team;

    #[Layout('layouts.admin-master')]
    public function render()
    {
        Auth::user()->can('membership.teams.view');
        return view('pages.admin.team')->with(['team' => $this->team]);
    }
}
