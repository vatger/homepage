<?php

namespace App\Livewire\Administration;

use App\Libraries\MembershipLibrary;
use App\Models\Membership\User\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MemberPage extends Component
{
    #[Locked]
    public User $user;

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('membership.users.details.view');
        return view('pages.admin.member')->with(['user' => $this->user]);
    }

    public function force_member_update(): void
    {
        MembershipLibrary::update($this->user, cache: false);
    }

}
