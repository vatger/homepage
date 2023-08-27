<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
                header="API Logs"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="API Logs" :subtitle="\App\OpenApi\Models\ApiLog::count()" />

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
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">
                            time
                            <i data-feather="{{$this->getSortIconClasses('id')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">
                            token_id
                            <i data-feather="{{$this->getSortIconClasses('id')}}"></i>
                        <th class="border-bottom p-3" style="white-space: nowrap">endpoint</th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">
                            ip_address
                            <i data-feather="{{$this->getSortIconClasses('id')}}"></i>
                        <th class="border-bottom p-3" style="white-space: nowrap">Aktion</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->time }}</td>
                            <td>{{ $log->token_id }}</td>
                            <td>{{ $log->endpoint }}</td>
                            <td>{{ $log->ip_address }}</td>
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
