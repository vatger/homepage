<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class MembershipPage extends Component
{
    protected array $queryString = ['tab'];
    public string $tab = 'profile';

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
