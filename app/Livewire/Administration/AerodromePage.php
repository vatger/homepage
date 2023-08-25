<?php

namespace App\Livewire\Administration;

use App\Models\Navigation\Aerodrome;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AerodromePage extends Component
{
    #[Locked]
    public Aerodrome $aerodrome;

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        return view('pages.admin.aerodrome')->with(['aerodrome' => $this->aerodrome]);
    }
}
