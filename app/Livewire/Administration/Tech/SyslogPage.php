<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Tech\SysLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class SyslogPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;
    #[Url]
    public ?int $log_id = null;

    #[Url]
    public ?string $type = null;

    public array $log_types = [];

    protected $sortable_fields = ['created_at', 'type', 'path', 'method'];

    public function mount(): void
    {
        $this->setInitialSortOrder('created_at', 'desc');
        $this->log_types = SysLog::select('type')->distinct()->get()->map(fn(object $o) => $o->type)->toArray();
    }

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = SysLog::where('created_at', 'LIKE', $this->search . '%');
        if ($this->type) {
            $query->where('type', 'LIKE', $this->type . '%');
        }
        $this->sortQueryModifier($query);
        $log = $this->log_id ? SysLog::find($this->log_id) : null;
        return view('pages.admin.syslogs')->with([
            'logs' => $query->paginate(),
            'sellog' => $log,
            'log_types' => $this->log_types,
        ]);
    }

    public function view_log($log_id): void
    {
        $this->log_id = $log_id;
    }

    public function close_log(): void
    {
        $this->log_id = null;
    }

    public function delete_log(): void
    {
        if ($this->log_id) {
            SysLog::find($this->log_id)->delete();
        }
    }
}
