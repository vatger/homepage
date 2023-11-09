<?php

namespace App\Livewire\Administration\Nav;

use App\Livewire\Helpers\NotyTrait;
use App\Livewire\Helpers\SearchTrait;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class AerodromePage extends Component
{
    use SearchTrait, NotyTrait, WithFileUploads;

    #[Locked]
    public Aerodrome $aerodrome;

    #[Rule('image|max:4096')]
    public ?TemporaryUploadedFile $photo = null; // 4MB Max

    protected array $searchable_fields = ['name', 'ident', 'frequency'];
    public string $station_search = '';

    public function boot(): void
    {
        $this->authorize('navigation.aerodromes.view');
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $stations = empty($this->station_search) ? null : Station::query();
        $this->searchQueryModifier($stations, $this->station_search);
        return view('pages.admin.aerodrome')->with([
            'aerodrome' => $this->aerodrome,
            'station_search_results' => $stations?->limit(3)->get(),
        ]);
    }

    public function save(): void
    {
        if (!$this->photo) {
            $this->showNoty('Kein Bild ausgewählt', 'error');
            return;
        }
        if (!in_array($this->photo->extension(), ['jpeg', 'jpg', 'JPEG', 'JPG'])) {
            $this->showNoty('Nur .jpeg erlaubt', 'error');
            return;
        }

        if ($this->photo->dimensions()[0] <= 1080 && $this->photo->dimensions()[1] <= 720) {
            $this->showNoty('Bild max. 1080 x 720 px', 'error');
            return;
        }
        $this->photo->storePubliclyAs('public/aerodromes', \Str::upper($this->aerodrome->icao) . '.jpeg');
        $this->showNoty('Bild gespeichert', 'success');
    }
}
