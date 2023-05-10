<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;
use App\Http\Livewire\Helpers\SortableTrait;
use App\Models\Navigation\Station;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Bookingslist extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait, AuthorizesRequests;

    public $search;

    public function render()
    {
        return view('administration.event.booking', ['listings' => Station::whereLike('model', $this->search ?? '')]);
    }
}
