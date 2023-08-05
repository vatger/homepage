<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class ProfileTab extends Component
{
    public function render()
    {
        $user = auth()->user();
        return view('components.profile.profiletab')->with(['user' => $user]);
    }
}
