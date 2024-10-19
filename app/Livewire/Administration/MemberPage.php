<?php

namespace App\Livewire\Administration;

use App\Jobs\UpdateGDPRRemovalsJob;
use App\Libraries\GDPRLibrary;
use App\Libraries\MembershipLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Membership\User;
use App\Models\Membership\UserBan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MemberPage extends Component
{
    use NotyTrait;

    #[Locked]
    public User $user;

    public BanForm $form;
    public ?UserBan $banInformation;

    public function saveBan(): void
    {
        $author = Auth::user();

        UserBan::query()->create([
            'user_id' => $this->user->id,
            'author_id' => $author->id,
            'ends_at' => $this->form->permanent ? null : $this->form->endDate,
            'homepage' => $this->form->homepage, // Why do we need this? The isCurrentlyBanned Attribute is true if any ban exists which has an end_date >= now
            'forum' => $this->form->forum,
            'teamspeak' => $this->form->teamspeak,
            'other_services' => $this->form->otherServices,
            'reason' => $this->form->reason,
        ]);

        MembershipLibrary::update($this->user, cache: false);

        $this->showNoty("Sperre erfolgreich angelegt");
    }

    public function showBanInformation(int $id): void
    {
        $this->banInformation = UserBan::query()->with('author')->find($id);
    }

    public function removeBan(): void
    {
        if ($this->banInformation == null) {
            $this->showNoty("Ein Fehler ist aufgetreten", 'error');
            return;
        }

        $this->banInformation->delete();
        MembershipLibrary::update($this->user, cache: false);

        $this->showNoty("Sperre erfolgreich aufgehoben");
    }

    public function endBanNow(): void
    {
        if ($this->banInformation == null) {
            $this->showNoty("Ein Fehler ist aufgetreten", 'error');
            return;
        }

        $this->banInformation->endBanNow();
        MembershipLibrary::update($this->user, cache: false);

        $this->showNoty("Sperre erfolgreich aufgehoben");
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('membership.users.details.view');
        return view('pages.admin.member')->with(['user' => $this->user, 'acting_user' => Auth::user()]);
    }

    public function force_member_update(): void
    {
        MembershipLibrary::update($this->user, cache: false);
        $this->showNoty("Nutzer aktualisiert");
    }

    public function mark_member_for_removal(): void
    {
        GDPRLibrary::mark_for_deletion($this->user);
        $this->showNoty("Nutzer zur Löschung markiert");
    }

    public function mark_member_for_removal_now(): void
    {
        GDPRLibrary::mark_for_deletion($this->user);
        $this->showNoty("Nutzer zur direkten Löschung markiert");
        dispatch(new UpdateGDPRRemovalsJob());
    }
}
