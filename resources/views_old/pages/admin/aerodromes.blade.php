<div class="container-fluid">
    <div class="layout-specing">

        <x-layouts.admin.content
                header="Flugplatzverwaltung"
                :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.navigation') => 'Navigation'
                ]"
        />

        <x-layouts.admin.card>
            <x-layouts.admin.card-header position="left" title="Flugplätze" :subtitle="\App\Models\Navigation\Aerodrome::count()" />

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class="fea icon-sm icons"></i>
                        <input wire:model.live="searchstr" class="form-control ps-5" type="text" placeholder="ICAO, IATA, Name">
                    </div>
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
                        <th class="border-bottom p-3" wire:click="sortBy('icao')">
                            ICAO | IATA
                            <i data-feather="{{ $this->getSortIconClasses('icao') }}"></i>
                        </th>
                        <th class="border-bottom p-3" wire:click="sortBy('active')">
                            Aktiv
                            <i data-feather="{{ $this->getSortIconClasses('active') }}"></i>
                        </th>
                        <th class="border-bottom p-3">FIR</th>
                        <th class="border-bottom p-3">Aktion</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($aerodromes as $aerodrome)
                        <tr class="text-center">
                            <td>{{ $aerodrome->name }}</td>
                            <td>{{ $aerodrome->icao }} | {{ $aerodrome->iata}}</td>
                            <td>{{ $aerodrome->active ? 'YES' : 'NO'}}</td>
                            <td>{{ $aerodrome->fir }}</td>
                            <td>
                                <a href="{{ route('administration.navigation.aerodromes.view', ['aerodrome' => $aerodrome]) }}" class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px">
                                    <i data-feather="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $aerodromes->links() }}
            </div>


        </x-layouts.admin.card>

    </div>
</div>
