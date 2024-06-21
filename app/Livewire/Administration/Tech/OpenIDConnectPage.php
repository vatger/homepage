<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use Laravel\Passport\Client;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class OpenIDConnectPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;

    protected $sortable_fields = [];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = Client::query();
        return view('pages.admin.openidconnect')->with(['clients' => $query->paginate()]);
    }
}
