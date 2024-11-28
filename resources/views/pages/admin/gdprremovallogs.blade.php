<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
            header="GDPR Removal Logs"
        ></x-layouts.admin.content>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="GDPR Removal Logs" :subtitle="\App\Models\Membership\GdprRemoval::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class=" fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="number" min="0" max="9000000">
                    </div>
                </li>
            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('started_at')">
                            started_at
                            <i data-feather="{{$this->getSortIconClasses('started_at')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('completed_at')">
                            completed_at
                            <i data-feather="{{$this->getSortIconClasses('completed_at')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('user_id')">
                            user_id
                            <i data-feather="{{$this->getSortIconClasses('user_id')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap">pending_services</th>
                        <th class="border-bottom p-3" style="white-space: nowrap">completed_services</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->started_at }}</td>
                            <td>{{ $log->canceled_at ? 'c' . $log->canceled_at : $log->completed_at }}</td>
                            <td>{{ $log->user_id }}</td>
                            <td>{{ join(',',$log->pending_services) }}</td>
                            <td>{{ join(',',$log->completed_services) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>

        </x-layouts.admin.card>

    </div>
</div>
