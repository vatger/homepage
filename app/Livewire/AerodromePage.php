<?php

namespace App\Livewire;

use App\Libraries\StandStatus\StandStatus;
use App\Libraries\StandStatus\StandStatusLibrary;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Libraries\VATSIM\MetarLibrary;
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

    public function load_stands(): array
    {
        return StandStatusLibrary::status($this->aerodrome);
    }

    public function load_aerodrome(): array
    {
        return $this->aerodrome->toArray();
    }

    public function load_metar(): ?string
    {
        return DataFeedLibrary::Metar($this->icao) ?? null;
    }
}
