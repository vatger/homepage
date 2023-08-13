<?php

namespace App\Livewire\Administration;

use App\Models\Groups\Team;
use App\Models\Membership\User\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TeamListPage extends Component
{
    #[Layout('layouts.admin-master')]
    public function render()
    {
        $teams = Team::all();
        return view('pages.admin.teams')->with(['groups' => $teams]);
    }
}
