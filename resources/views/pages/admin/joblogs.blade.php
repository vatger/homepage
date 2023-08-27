<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
                header="Jobs"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="Failed Jobs" :subtitle="\App\Models\Tech\FailedJob::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class=" fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="date">
                    </div>
                </li>
            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('failed_at')">
                            failed_at
                            <i data-feather="{{$this->getSortIconClasses('failed_at')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('connection')">
                            connection
                            <i data-feather="{{$this->getSortIconClasses('connection')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('queue')">
                            queue
                            <i data-feather="{{$this->getSortIconClasses('queue')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('payload')">
                            payload
                            <i data-feather="{{$this->getSortIconClasses('payload')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">exception</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->failed_at }}</td>
                            <td>{{ $log->connection }}</td>
                            <td>{{ $log->queue }}</td>
                            <td>{{ $log->payload }}</td>
                            <td>{{ \Illuminate\Support\Str::words($log->exception, words: 35) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-sm btn-soft-info" wire:click="view_log({{ $log->id }})">Info</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>

        </x-layouts.admin.card>

        <x-layouts.admin.card>
            <x-layouts.admin.card-header position="left" title="Scheduled Jobs" :subtitle="count($schedule_events)"></x-layouts.admin.card-header>
            <x-layouts.admin.card-header position="right">
                <div class="flex-1 ms-3">
                    <h6 class="mb-0 text-muted">Uptime</h6>
                    <p class="fs-5 text-dark fw-bold mb-0">{{ shell_exec('uptime -p') ?? 'N/A' }}</p>
                </div>
                <div class="flex-1 ms-3">
                    <h6 class="mb-0 text-muted">Status</h6>
                    <p class="fs-5 text-dark fw-bold mb-0">{{ !file_exists(storage_path() . '/framework/down') ? 'YES' : 'NO or N/A' }}</p>
                </div>
            </x-layouts.admin.card-header>

            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <th class="border-bottom p-3" style="white-space: nowrap">command</th>
                    </thead>
                    <tbody>
                    @foreach ($schedule_events as $event)
                        <tr>
                            {{-- <td>{{ json_encode($event) }}</td> --}}
                            <td>{{ Str::after($event?->command, '"artisan"') }}</td>
                        </tr>
                    @endforeach
                    <thead>
                    <th class="border-bottom p-3" style="white-space: nowrap">due command</th>
                    </thead>
                    @foreach ($schedule_events_due as $event)
                        <tr>
                            {{-- <td>{{ json_encode($event) }}</td> --}}
                            <td>{{ Str::after($event?->command, '"artisan"') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </x-layouts.admin.card>

    </div>
</div>
