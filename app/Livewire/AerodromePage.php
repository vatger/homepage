<?php

namespace App\Livewire;

use App\Libraries\NavLibrary;
use App\Libraries\StandStatusLibrary;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Libraries\VATSIM\EventLibrary;
use App\Models\Navigation\Aerodrome;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use VatsimData\Datafeed;
use VatsimData\Metar;

class AerodromePage extends Component
{
    #[Locked]
    public string $icao;

    public string $selected_link;

    #[Locked]
    public Aerodrome $aerodrome;


    public function mount()
    {
        $this->aerodrome = Aerodrome::icao($this->icao)->firstOrFail();

    }

    #[Layout('layouts.master')]
    public function render()
    {
        $data = NavLibrary::download_airport_data($this->icao);
        $links = $data ? collect($data->links)->groupBy('category') : [];
        return view('pages.aerodrome')->with([
            'aerodrome' => $this->aerodrome,
            'links' => $links
        ]);
    }

    public function load_stands(): array
    {
        return StandStatusLibrary::standstatus($this->aerodrome);
    }

    public function load_aircraft(): array
    {
        return StandStatusLibrary::aircraftstatus($this->aerodrome);
    }

    public function load_aerodrome(): array
    {
        return $this->aerodrome->toArray();
    }

    public function load_metar(): ?string
    {
        return Metar::get($this->icao) ?? null;
    }

    public function load_indicators(): array
    {
        return DataFeedLibrary::ControllersAerodrome($this->aerodrome);
    }

    public function load_atis(): array
    {
        return Datafeed::AtisAerodrome($this->icao);
    }

    public function load_events(): array
    {
        return EventLibrary::getAerodromeEvents($this->aerodrome->icao);
    }
}
