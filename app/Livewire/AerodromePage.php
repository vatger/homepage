<?php

namespace App\Livewire;

use App\Models\Navigation\Aerodrome;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AerodromePage extends Component
{
    #[Locked]
    public string $icao;
    #[Locked]
    public Aerodrome $aerodrome;

    public function mount()
    {
        $this->aerodrome = Aerodrome::icao($this->icao)->firstOrFail();
    }

    #[Layout('layouts.master')]
    public function render()
    {
        return view('pages.aerodrome')->with(['aerodrome' => $this->aerodrome]);
    }
}
