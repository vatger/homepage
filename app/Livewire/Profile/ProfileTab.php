<?php

namespace App\Livewire\Profile;

use App\Livewire\Helpers\ModalTrait;
use App\Models\Groups\Fir;
use App\Models\Membership\User\Concerns\FirMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileTab extends Component
{
    use ModalTrait;

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
        dd($this->user_fir);
        return view('components.profile.profiletab')->with(['user' => $user, 'userfir' => $this->user_fir]);
    }

    public function changeFir(): void
    {
        FirMembership::where('user_id', Auth::user()->id)->delete();
        if ($this->fir_selection == -1) {
            return;
        }
        $f = new FirMembership();
        $f->user_id = Auth::user()->id;
        $f->fir_id = $this->fir_selection;
        $f->save();
    }
}
