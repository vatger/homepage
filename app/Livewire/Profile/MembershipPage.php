<?php

namespace App\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class MembershipPage extends Component
{
    #[Url]
    public string $tab = 'profile';

    #[Layout('layouts.master')]
    public function render(): View
    {
        $user = auth()->user();

        return view('pages.membership')->with(['user' => $user, 'tab' => $this->tab]);
    }

    public function sel(string $sel): void
    {
        $this->tab = $sel;
    }
}
