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
                        <i data-feather="search" class=" fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="date">
                    </div>
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
                            <td>{{ \Illuminate\Support\Str::words($log->message, words: 35) }}</td>
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
    </div>
</div>
