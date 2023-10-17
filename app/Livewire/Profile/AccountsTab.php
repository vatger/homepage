<?php

namespace App\Livewire\Profile;

use App\Libraries\XenForoLibrary;
use App\Livewire\Helpers\NotyTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccountsTab extends Component
{
    use NotyTrait;

    public string $password = '';

    public function render(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        $user = Auth::user();
        $username = XenForoLibrary::getForumUsername($user);
        $tsids = $user->teamspeakRegistrations;
        return view('components.profile.accountstab')->with([
            'username' => $username,
            'teamspeakids' => $tsids,
        ]);
    }

    public function create_board_account(): void
    {
        $user = Auth::user();
        XenForoLibrary::createForumAccount($user, $this->password);
    }
}
