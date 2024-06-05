<?php

namespace App\Livewire\Administration;

use App\Libraries\GDPRLibrary;
use App\Libraries\MembershipLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Membership\User\User;
use App\Models\Membership\User\UserBan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Form;

class BanForm extends Form
{
    public string $endDate = '';
    public bool $permanent = false;
    public bool $teamspeak = true;
    public bool $forum = true;
    public bool $homepage = true;
    public bool $otherServices = true;
    public string $reason = '';

    public function __construct(Component $component, $propertyName)
    {
        parent::__construct($component, $propertyName);
        $this->endDate = Carbon::now()->addDay()->roundHour()->format('Y-m-d H:i');
    }
}

class MemberPage extends Component
{
    use NotyTrait;

    #[Locked]
    public User $user;

    public BanForm $form;
    public ?UserBan $banInformation;

    public function saveBan()
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

    public function showBanInformation(int $id)
    {
        $this->banInformation = UserBan::query()->with('author')->find($id);
    }

    public function removeBan()
    {
        if ($this->banInformation == null) {
            $this->showNoty("Ein Fehler ist aufgetreten", 'error');
            return;
        }

        $this->banInformation->delete();
        MembershipLibrary::update($this->user, cache: false);

        $this->showNoty("Sperre erfolgreich aufgehoben");
    }

    public function endBanNow()
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
    }

    public function mark_member_for_removal(): void
    {
        GDPRLibrary::mark_for_deletion($this->user);
        $this->showNoty("Nutzer zur Löschung markiert");
    }
}
