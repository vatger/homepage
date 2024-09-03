<?php

namespace App\Livewire\Profile;

use App\Libraries\XenForoLibrary;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Session;
use Str;

class SettingsTab extends Component
{
    #[Rule('required')]
    public bool $darkmode;
    #[Rule('required')]
    public string $color;
    #[Rule('required|in:de,en')]
    public string $language;

    public function mount(): void
    {
        $this->darkmode = Auth::user()->settings->dark_mode;
        $this->color = Auth::user()->settings->color;
        $this->language = Auth::user()->settings->language;
    }

    public function render(): View
    {
        $user = Auth::user();

        $ical = $user->passwords->ical_token ? route('api.booking.ical', [
            'id' => $user->id,
            'token' => $user->passwords->ical_token
        ]) : null;


        $board_username = XenForoLibrary::getForumUsername($user);


        return view('components.profile.settingstab')->with(
            [
                'user' => $user,
                'board_username' => $board_username,
                'ical' => $ical
            ]);
    }

    public function updated($name, $value): void
    {
        Auth::user()
            ->settings()
            ->update([
                'language' => $this->language,
                'dark_mode' => intval($this->darkmode),
                'color' => $this->color,
            ]);
        Session::put('language', $this->language);
        $this->js('window.location.reload()');
    }

    public function new_ical_token(): void
    {
        $user = Auth::user();
        $user->passwords->ical_token = Str::random(32);
        $user->passwords->save();
    }
}
