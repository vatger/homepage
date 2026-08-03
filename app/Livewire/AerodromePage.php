<?php

namespace App\Livewire;

use App\Libraries\NavLibrary;
use App\Libraries\StandStatusLibrary;
use App\Libraries\VATSIM\DataFeedLibrary;
use App\Libraries\VATSIM\EventLibrary;
use App\Models\Navigation\Aerodrome;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
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
            'links' => $links,
        ]);
    }

    #[Renderless]
    public function load_stands(?string $since = null): array
    {
        return $this->timestampedResponse($since, Datafeed::UpdatedAt(), fn () => StandStatusLibrary::standstatus($this->aerodrome));
    }

    #[Renderless]
    public function load_aircraft(?string $since = null): array
    {
        return $this->timestampedResponse($since, Datafeed::UpdatedAt(), fn () => StandStatusLibrary::aircraftstatus($this->aerodrome));
    }

    #[Renderless]
    public function load_aerodrome(): array
    {
        return $this->aerodrome->toArray();
    }

    #[Renderless]
    public function load_metar(?string $since = null): array
    {
        return $this->timestampedResponse($since, Metar::FetchedAt($this->icao), fn () => Metar::get($this->icao));
    }

    #[Renderless]
    public function load_indicators(?string $since = null): array
    {
        return $this->timestampedResponse($since, Datafeed::UpdatedAt(), fn () => DataFeedLibrary::ControllersAerodrome($this->aerodrome));
    }

    #[Renderless]
    public function load_atis(?string $since = null): array
    {
        return $this->timestampedResponse($since, Datafeed::UpdatedAt(), fn () => Datafeed::AtisAerodrome($this->icao));
    }

    #[Renderless]
    public function load_events(): array
    {
        return EventLibrary::getAerodromeEvents($this->aerodrome->icao, 3);
    }

    private function timestampedResponse(?string $since, ?\DateTimeImmutable $updatedAt, \Closure $loader): array
    {
        $updatedAtString = $updatedAt?->format(\DateTimeImmutable::ATOM);

        if ($updatedAtString !== null && $since !== null && $since === $updatedAtString) {
            return [
                'updated_at' => $updatedAtString,
                'unchanged' => true,
            ];
        }

        return [
            'updated_at' => $updatedAtString,
            'unchanged' => false,
            'data' => $loader(),
        ];
    }
}
