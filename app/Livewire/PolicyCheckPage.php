<?php

namespace App\Livewire;

use App\Models\Membership\UserSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class PolicyCheckPage extends Component
{
    private array $policies = [];

    private ?UserSetting $userSetting;

    #[Url]
    public ?string $url;

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
            'en' => Session::get('language', 'de') == 'en',
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

    public function continue()
    {
        if ($this->url) {
            return redirect()->intended(urldecode($this->url));
        }

        return redirect()->intended(route('landing'));
    }
}
