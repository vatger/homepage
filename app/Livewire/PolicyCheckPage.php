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

    #[Url]
    public ?string $url = null;

    public function boot(): void
    {
        $this->policies = UserSetting::getPolicies(true);
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.policy_check')->with([
            'polices' => $this->policies,
            'user_settings' => $this->settings(),
            'en' => Session::get('language', 'de') == 'en',
        ]);
    }

    public function acceptPolicy(string $policyId): void
    {
        $this->settings()->agreeTo($policyId);
    }

    public function declinePolicy(string $policyId): void
    {
        $this->settings()->agreeTo($policyId, true);
    }

    public function continueToApplication()
    {
        if ($this?->url) {
            return redirect()->intended(urldecode($this->url));
        }

        return redirect()->intended(route('landing'));
    }

    private function settings(): UserSetting
    {
        return UserSetting::query()->firstOrCreate(['user_id' => Auth::id()]);
    }
}
