<?php

namespace App\Livewire;

use App\Models\Membership\UserSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsPage extends Component
{
    private array $policies = [];

    private ?UserSetting $userSetting;

    public function boot(): void
    {
        $this->policies = UserSetting::getPolicies(true);
        $this->userSetting = Auth::user()?->settings;
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.policy_check')->with([
            'polices' => $this->policies,
            'user_settings' => $this->userSetting,
        ]);
    }

    public function accept(string $policy_id): void
    {
        $this->userSetting->agreeTo($policy_id);
    }

    public function decline(string $policy_id): void
    {
        $this->userSetting->agreeTo($policy_id, true);
    }
}
