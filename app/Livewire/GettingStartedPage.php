<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class GettingStartedPage extends Component
{
    #[Url]
    public int $step = 1;

    #[Layout('layouts.master')]
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $logged_in = Auth::check();
        $user = Auth::user();

        $completed1 = $logged_in;
        $completed2 = $completed1 && $user?->settings?->agreed;
        $completed3 = $completed1 && $user?->vatsimDetails?->rating_pilot >= 0;
        $completed4 = $completed1 && $user?->vatsimDetails?->subdivision_code == 'GER';

        $steps_completed = count(array_filter([$completed1, $completed2, $completed3, $completed4]));

        return view('pages.getting-started')->with([
            'logged_in' => $logged_in,
            'completed1' => $completed1,
            'completed2' => $completed2,
            'completed3' => $completed3,
            'completed4' => $completed4,
            'steps_completed' => $steps_completed,
            'steps_total' => 9,
        ]);
    }

    public function setStep(int $step): void
    {
        $this->step = $step;
    }
}
