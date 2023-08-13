<?php

namespace App\Livewire\Administration;

use App\Models\Membership\User\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MemberPage extends Component
{
    public User $user;

    #[Layout('layouts.admin-master')]
    public function render()
    {
        return view('pages.admin.member')->with(['user' => $this->user]);
    }
}
