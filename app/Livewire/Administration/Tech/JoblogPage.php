<?php

namespace App\Livewire\Administration\Tech;

use App\Console\Kernel;
use App\Livewire\Helpers\PaginationTrait;
use App\Livewire\Helpers\SortableTrait;
use App\Models\Tech\FailedJob;
use App\OpenApi\Models\ApiLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Events\Dispatcher;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

class JoblogPage extends Component
{
    use PaginationTrait, SortableTrait;

    #[Url]
    public $search;

    protected $sortable_fields = ['failed_at', 'connection', 'queue', 'payload'];

    #[Layout('layouts.admin.admin-master')]
    public function render()
    {
        $query = FailedJob::where('failed_at', 'LIKE', $this->search . '%');
        $this->sortQueryModifier($query);

        $app = new Kernel(app(), new Dispatcher());
        $schedule = app(Schedule::class);
        $schedule_events = collect($schedule->events());
        $schedule_events_due = $schedule->dueEvents(app());

        return view('pages.admin.joblogs')->with([
            'logs' => $query->paginate(),
            'schedule_events' => $schedule_events,
            'schedule_events_due' => $schedule_events_due,
        ]);
    }

    public function view_log($log_id)
    {
        return;
    }
}
