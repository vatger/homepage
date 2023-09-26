<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Session;

class AccountsTab extends Component
{
    public function mount(): void
    {
    }

    public function render(): View
    {
        $user = Auth::user();
        return view('components.profile.accountstab')->with(['user' => $user]);
    }
}
