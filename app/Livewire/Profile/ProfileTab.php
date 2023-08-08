<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileTab extends Component
{
    public function render()
    {
        $user = Auth::user();
        dd($user);
        return view('components.profile.profiletab')->with(['user' => $user]);
    }
}
