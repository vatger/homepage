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
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('created_at')">
                            time
                            <i data-feather="{{$this->getSortIconClasses('created_at')}}"></i>
                        </th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">
                            token_id
                            <i data-feather="{{$this->getSortIconClasses('id')}}"></i>
                        <th class="border-bottom p-3" style="white-space: nowrap">endpoint</th>
                        <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">
                            ip_address
                            <i data-feather="{{$this->getSortIconClasses('id')}}"></i>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at }}</td>
                            <td>{{ $log->token_id }}</td>
                            <td>{{ $log->endpoint }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>

        </x-layouts.admin.card>


        <x-layouts.admin.card>

            <x-layouts.admin.card-header position="left" title="API Keys" :subtitle="\App\OpenApi\Models\ApiToken::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">

                    </div>
                </li>
            </x-layouts.admin.card-header>


            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text">
                        <th class="border-bottom p-3" style="white-space: nowrap">id</th>
                        <th class="border-bottom p-3" style="white-space: nowrap">token</th>
                        <th class="border-bottom p-3" style="white-space: nowrap">description</th>
                        <th class="border-bottom p-3" style="white-space: nowrap">valid_till</th>
                        <th class="border-bottom p-3" style="white-space: nowrap">routes</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($keys as $key)
                        <tr>
                            <td>{{ $key->id }}</td>
                            <td><code>{{ $key->token }}</code></td>
                            <td>{{ $key->description }}</td>
                            <td>{{ $key->valid_till }}</td>
                            <td><code>@json(collect($key->routes)->map(fn($obj)=>$obj->route_id))</code></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </x-layouts.admin.card>
    </div>
</div>
