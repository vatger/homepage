<?php

namespace App\Livewire\Helpers;

use Livewire\WithPagination;

trait PaginationTrait
{
    use WithPagination;

    protected $paginationTheme = 'custom';

    public function updatingPaginationTrait(): void
    {
        $this->resetPage();
    }
}
