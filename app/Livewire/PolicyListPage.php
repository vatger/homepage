<?php

namespace App\Livewire;

use App\Models\Membership\UserSetting;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PolicyListPage extends Component
{
    private array $policies = [];

    public function boot(): void
    {
        $this->policies = UserSetting::getPolicies(false);
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.policy_list')->with([
            'policies' => $this->policies,
            'en' => Session::get('language', 'de') == 'en',
        ]);
    }
}
