<div class="container-fluid">
    <div class="layout-specing">

        <x-layouts.admin.content
                header="Stationsverwaltung"
                :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.navigation') => 'Navigation'
                ]"
        />

        <x-layouts.admin.card>
            <x-layouts.admin.card-header position="left" title="Stationen" :subtitle="\App\Models\Navigation\Station::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class="fea icon-sm icons"></i>
                        <input wire:model.live="searchstr" class="form-control ps-5" type="text" placeholder="Name, Ident, Frequency">
                    </div>
                </li>
                <li class="list-inline-item" style="width: 100%">
                    <a href="https://github.com/VATGER-Nav/datahub">Stationen verwalten</a>
                </li>
            </x-layouts.admin.card-header>

            <div class="p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text-center">
                        <th class="border-bottom p-3" wire:click="sortBy('name')">
                            Name
                            <i data-feather="{{ $this->getSortIconClasses('name') }}"></i>
                        </th>
                        <th class="border-bottom p-3" wire:click="sortBy('ident')">
                            Ident
                            <i data-feather="{{ $this->getSortIconClasses('ident') }}"></i>
                        </th>
                        <th class="border-bottom p-3" wire:click="sortBy('active')">
                            Aktiv
                            <i data-feather="{{ $this->getSortIconClasses('active') }}"></i>
                        </th>
                        <th class="border-bottom p-3">Frequency</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($stations as $s)
                        <tr class="text-center">
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->ident }} </td>
                            <td>{{ $s->active ? 'YES' : 'NO'}}</td>
                            <td>{{ $s->fixed_frequency }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $stations->links() }}
            </div>


        </x-layouts.admin.card>

    </div>
</div>
