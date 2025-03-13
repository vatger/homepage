<?php

namespace App\Livewire;

use App\Models\Membership\UserSetting;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PolicyPage extends Component
{
    private ?object $policy;

    public string $policy_id;

    public function boot(): void
    {
        $this->policy = array_find(UserSetting::getPolicies(), fn ($item) => $item->id == $this->policy_id);
        if ($this->policy == null) {
            $this->redirectRoute('landing');
        }
    }

    public function mount(string $policy_id): void
    {
        $this->policy_id = $policy_id;
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.policy')->with([
            'policy' => $this->policy,
            'en' => Session::get('language', 'de') == 'en',
        ]);
    }
}
