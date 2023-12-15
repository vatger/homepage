<?php

namespace App\Livewire\Administration;

use App\Libraries\MembershipLibrary;
use App\Libraries\OSTicketLibrary;
use App\Libraries\VikunjaLibrary;
use App\Livewire\Helpers\NotyTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

class StaffDataProtection extends Component
{
    use NotyTrait;

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.sdp');
    }
    public function accept()
    {
        $user = Auth::user();
        $user->staffDetails->accepted_data_protection_at = now();
        $user->staffDetails->save();
        MembershipLibrary::update($user);
    }
}
