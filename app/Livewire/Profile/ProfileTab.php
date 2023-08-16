<?php

namespace App\Livewire\Profile;

use App\Livewire\Helpers\ModalTrait;
use App\Models\Membership\User\Concerns\UserFirPivot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileTab extends Component
{
    use ModalTrait;

    public int $fir_selection = -1;
    public bool $fir_selection_checkbox = false;

    public function boot(): void
    {
        $this->fir_selection = Auth::user()->fir?->id ?? -1;
    }

    public function render()
    {
        $user = Auth::user();
        return view('components.profile.profiletab')->with(['user' => $user]);
    }

    public function changeFir(): void
    {
        UserFirPivot::where('user_id', Auth::user()->id)->delete();
        if ($this->fir_selection == -1) {
            return;
        }
        $f = new UserFirPivot();
        $f->user_id = Auth::user()->id;
        $f->fir_id = $this->fir_selection;
        $f->save();
        //$this->closeModalNoCheck('change-fir-modal');
    }

    public function openFirSelection(): void
    {
        //$this->openModalNoCheck('change-fir-modal');
    }
}
