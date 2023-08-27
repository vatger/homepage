<?php

namespace App\Livewire\Administration;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\SysLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class SyslogPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;

    protected $sortable_fields = ['created_at', 'type', 'path', 'method'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $query = SysLog::where('created_at', 'LIKE', $this->search . '%');
        $this->sortQueryModifier($query);
        return view('pages.admin.syslogs')->with(['logs' => $query->paginate()]);
    }

    public function view_log($log_id)
    {
        return;
    }
}
