<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Helpers\PaginationTrait;
use App\Http\Livewire\Helpers\SearchTrait;
use App\Http\Livewire\Helpers\SortableTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Eventrouteslist extends Component
{
    use PaginationTrait, SortableTrait, SearchTrait, AuthorizesRequests;
}
