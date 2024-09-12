<?php

namespace App\Livewire\Profile;

use App\Libraries\MembershipLibrary;
use App\Libraries\XenForoLibrary;
use App\Livewire\Helpers\NotyTrait;
use App\Models\Groups\Fir;
use App\Models\Membership\Concerns\FirMembership;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileTab extends Component
{
    use NotyTrait;

    public int $fir_selection = -1;
    public bool $fir_selection_checkbox = false;

    private ?Fir $user_fir;

    public function mount(): void
    {
        $this->fir_selection = Auth::user()->fir?->id ?? -1;
    }

    public function render()
    {
        $user = Auth::user();
        $this->user_fir = Auth::user()->fir;
        return view('components.profile.profiletab')->with(['user' => $user, 'userfir' => $this->user_fir]);
    }

    public function changeEmail(): void
    {
        $user = Auth::user();
        XenForoLibrary::updateForumAccount($user, true);
    }

    public function changeFir(): void
    {
        if (!Auth::user()->vatgerDetails->can_change_fir) {
            $this->showNoty('Can not change FIR.', 'error');
            return;
        }
        if (!$this->fir_selection_checkbox) {
            $this->showNoty('Please check the box!', 'error');
            return;
        }
        FirMembership::where('user_id', Auth::user()->id)->delete();
        if ($this->fir_selection == -1) {
            $this->showNoty('FIR verlassen.', 'success');
        } else {
            $f = new FirMembership();
            $f->user_id = Auth::user()->id;
            $f->fir_id = $this->fir_selection;
            $f->save();
            $this->showNoty('FIR beigetreten.', 'success');
        }
        MembershipLibrary::update(Auth::user(), true);
        $this->fir_selection_checkbox = false;
    }
}
