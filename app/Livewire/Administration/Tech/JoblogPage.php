<?php

namespace App\Livewire\Administration\Tech;

use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Tech\FailedJob;
use Illuminate\Console\Scheduling\Schedule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class JoblogPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;

    #[Url]
    public ?int $log_id = null;

    protected $sortable_fields = ['failed_at', 'connection', 'queue', 'payload'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $this->authorize('tech.access');
        $query = FailedJob::where('failed_at', 'LIKE', $this->search . '%');
        $this->sortQueryModifier($query);

        $log = $this->log_id ? FailedJob::find($this->log_id) : null;

        $schedule = app(Schedule::class);
        $schedule_events = collect($schedule->events());
        $schedule_events_due = $schedule->dueEvents(app());

        return view('pages.admin.joblogs')->with([
            'logs' => $query->paginate(),
            'sellog' => $log,
            'schedule_events' => $schedule_events,
            'schedule_events_due' => $schedule_events_due,
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
            FailedJob::find($this->log_id)->delete();
        }
    }
}
