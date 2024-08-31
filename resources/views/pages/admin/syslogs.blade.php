<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
            header="SYS Logs"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="SYS Logs" :subtitle="\App\Models\Tech\SysLog::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="calendar" class=" fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="date">
                    </div>
                </li>
                <li class="list-inline-item" style="width: 100%">
                    <select wire:model.live="type" class="form-select form-control mt-2" aria-label="Type">
                        <option value="">-</option>
                        <option value="http">http</option>
                        <option value="exception">exception</option>
                        <option value="log">log</option>
                    </select>
                </li>
            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('created_at')">
                            created_at
                            <i data-feather="{{$this->getSortIconClasses('created_at')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('type')">
                            type
                            <i data-feather="{{$this->getSortIconClasses('type')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('path')">
                            path
                            <i data-feather="{{$this->getSortIconClasses('path')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('method')">
                            method
                            <i data-feather="{{$this->getSortIconClasses('method')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->type }}</td>
                            <td>{{ $log->path }}</td>
                            <td>{{ $log->method }}</td>
                            <td>{{ \Illuminate\Support\Str::words($log->message, words: 10) }}</td>
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

        @if($sellog)
            <div class="modal show" tabindex="-1" aria-modal="true" role="dialog" style="display: block;">
                <div class="container">
                    <div class="modal-content rounded shadow border-0">
                        <div class="modal-header border-bottom">
                            <h5 class="modal-title">Log Eintrag #{{$sellog->id}}</h5>
                            <button wire:click="close_log()" type="button" class="btn btn-icon btn-close">
                                <i class="uil uil-times fs-4 text-dark"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-white px-3 rounded box-shadow">
                                <p><strong>ID:</strong> <span>{{ $sellog->id }}</span></p>
                                <p><strong>User:</strong> <span>{{ $sellog->user_id }}</span></p>
                                <p><strong>Method:</strong> <span>{{ $sellog->method }}</span></p>
                                <p><strong>Time:</strong> <span>{{ $sellog->created_at }}</span></p>
                                <p><strong>Stacktrace:</strong>
                                <p>{!! str_replace('#','<hr>#',$sellog->stack_trace) !!}</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="close_log()" type="button" class="btn btn-sm btn-secondary">Schließen</button>
                            <button wire:click="delete_log()" type="submit" class="btn btn-sm btn-danger">Löschen</button>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>
