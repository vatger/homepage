<div class="container-fluid">
    <div class="layout-specing">

        <x-layouts.admin.content
                header="Flugplatzverwaltung"
                :links="[
                    route('administration.dashboard') => 'Administration',
                    route('administration.navigation') => 'Navigation'
                ]"
        />

        <div class="row">
            <div class="col mt-4">
                <div class="card shadow border-0">
                    <div class="row p-4 border-bottom">
                        <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                <div class="d-flex align-items-center">
                                    <div class="icon text-center rounded-pill">
                                        <i class="mdi mdi-airport fs-4 mb-0"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <h6 class="mb-0 text-muted">Flugplätze</h6>
                                        <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ App\Models\Navigation\Aerodrome::count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
                            <li class="list-inline-item" style="width: 100%">
                                <div class="form-icon position-relative">
                                    <i data-feather="search" class="fea icon-sm icons"></i>
                                    <input wire:model.live="searchstr" class="form-control ps-5" type="text" placeholder="ICAO, IATA, Name">
                                </div>
                            </li>
                        </div>
                    </div>

                    <div class="p-4 text-center" id="error-container" style="display: none">
                        <div class="alert alert-danger mt-3" role="alert" id="error-message">Ein Fehler ist beim Laden der Daten
                            aufgetreten. Wir probieren es in <span id="error-countdown">60</span> Sekunden automatisch erneut. Der Fehler
                            wurde automatisch an das Web-Department weitergegeben.
                        </div>
                    </div>

                    <div id="content-container">
                        <div class="row p-4 col-lg-4 col-md-4 col-sm-12" style="float: right; max-width: 300px">
                            <button class="btn btn-sm btn-soft-primary">Flugplatz hinzufügen</button>
                        </div>

                        <div class="row p-4 pt-0 table-responsive">
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
                                    <th class="border-bottom p-3 w-25">Aktion</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($aerodromes as $aerodrome)
                                    <tr>
                                        <td>{{ $aerodrome->name }}</td>
                                        <td>{{ $aerodrome->icao }} | {{ $aerodrome->iata }}</td>
                                        <td>{{ $aerodrome->active }}</td>
                                        <td>{{ $aerodrome->fir }}</td>
                                        <td><a href="{{route('administration.navigation.aerodromes.view', ['aerodrome' => $aerodrome])}}"> clcijck</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $aerodromes->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
</div>
