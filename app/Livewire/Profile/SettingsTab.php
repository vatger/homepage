<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Session;

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
        return view('components.profile.settingstab')->with(['user' => $user]);
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
}
