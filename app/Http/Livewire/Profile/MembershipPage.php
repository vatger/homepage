<?php

namespace App\Http\Livewire\Profile;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;

use Livewire\Component;

class MembershipPage extends Component
{
    protected $queryString = ['tab'];
    public $tab = 'profile';

    public function render()
    {
        $user = auth()->user();
        return view('components.profile.membershippage_lw')->with(['user' => $user, 'tab' => $this->tab]);
    }

    public function sel(string $sel): void
    {
        $this->tab = $sel;
    }
}
