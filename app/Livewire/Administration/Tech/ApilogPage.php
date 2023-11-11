<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\OpenApi\Models\ApiLog;
use App\OpenApi\Models\ApiToken;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class ApilogPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;

    protected $sortable_fields = ['id', 'created_at'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $query = ApiLog::where('created_at', 'LIKE', $this->search . '%');
        $this->sortQueryModifier($query);
        return view('pages.admin.apilogs')->with(['logs' => $query->paginate(), 'keys' => ApiToken::all()]);
    }
}
