<?php

namespace App\Livewire\Profile;

use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Libraries\XenForoLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\TeamspeakRegistration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccountsTab extends Component
{
    use NotyTrait;

    public string $password = '';

    public string $teamspeak = '';

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

    public function create_teamspeak_account(): void
    {
        $user = Auth::user();
        $result = TeamSpeakWebQuery::registerViaUid($user, '0.0.0.0', $this->teamspeak);
        if (! $result) {
            $this->showNoty('TeamSpeak ID konnte nicht verknüpft werden', 'error');

            return;
        }
    }

    public function delete_teamspeak_account(int $id): void
    {
        $ts = TeamspeakRegistration::find($id);
        if ($ts->user_id != Auth::user()->id) {
            $this->showNoty('TeamSpeak ID konnte nicht gelöscht werden', 'error');

            return;
        }
        $result = TeamSpeakWebQuery::removeRegistation($ts);
        if (! $result) {
            $this->showNoty('TeamSpeak ID konnte nicht gelöscht werden', 'error');

            return;
        }
    }
}
