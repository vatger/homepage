<div>
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{
    $aerodrome->background_image_url ?? asset('images/profile/profile_1.png')
    }}")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 85%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">{{ $aerodrome->name }}</h2>
                        <ul class="list-unstyled mt-4 mb-0">
                            <li class="list-inline-item h4 user me-2 text-light">
                                <span class="badge rounded bg-soft-danger p-2" id="del_indicator"> DEL </span>
                                <span class="badge rounded bg-soft-danger p-2" id="gnd_indicator"> GND </span>
                                <span class="badge rounded bg-soft-danger p-2" id="twr_indicator"> TWR </span>
                                <span class="badge rounded bg-soft-danger p-2" id="app_indicator"> APP </span>
                                <span class="badge rounded bg-soft-danger p-2" id="ctr_indicator"> CTR </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        <li class='breadcrumb-item active'>{{ $aerodrome->icao }}</li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Hero End -->

    <!-- Shape Start -->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!--Shape End-->

    <section class="section">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-8 col-md-6 mb-4">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <div class="card-body content">
                            <h4>General Information</h4>
                            <div class="w-100">
                                <div class="row" id="counter">
                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 mt-4">{{ $aerodrome->icao }}</h4>
                                            <h6 class="counter-head text-muted">ICAO</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 mt-4">
                                                {{ $aerodrome->iata ?? '-' }}
                                            </h4>
                                            <h6 class="counter-head text-muted">IATA</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 mt-4">{{ $aerodrome->elevation }}</h4>
                                            <h6 class="counter-head text-muted">Elevation (ft)</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 mt-4">
                                                @if ($aerodrome->civilian == 1)
                                                    @lang('general.phrases.yes')
                                                @else
                                                    @lang('general.phrases.no')
                                                @endif
                                            </h4>
                                            <h6 class="counter-head text-muted">Civil</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card blog blog-detail border-0 shadow rounded mt-4">
                        <div class="card-body content">
                            <h4>Stand Information</h4>
                            @vite('resources/scss/special/aerodrome-mapbox.scss')
                            <div class="w-100" id="map-container">
                                <div wire:ignore id="map" class="w-100 mt-3 rounded" style="height: 500px">
                                    <h5 class="mt-3" id="event-title">@lang('pilot.aerodromes.aerodrome.loading-event-text')</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card blog blog-detail border-0 shadow rounded mt-4">
                        <div class="card-body content">
                            <h4>@lang('pilot.aerodromes.aerodrome.upcoming-event-title-text')</h4>
                            <div wire:ignore class="w-100" id="event-container">
                                <img src="" class="card-img-top loader-show overflow-hidden mt-3 w-100" id="event-banner" style="min-width: 100%">
                                <h5 class="mt-3" id="event-title">@lang('pilot.aerodromes.aerodrome.loading-event-text')</h5>
                                <div class="mt-3 text-muted" id="event-text"></div>

                                <div class="alert alert-light shadow" id="event-routes" role="alert" style="display: none"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BLog End -->

                <!-- START SIDEBAR -->
                <div class="col-lg-4 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="card border-0 sidebar sticky-bar ms-lg-4">
                        <div class="card-body p-0">
                            <!-- RECENT POST -->
                            <div class="widget">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    METAR
                                </span>

                                <div class="mt-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1 ms-3" style="margin-right: 1rem !important;">

                                            <a wire:ignore class="d-block title text-dark" id="metar-container">Loading...</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RECENT POST -->

                            <!-- RECENT POST -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Links
                                </span>

                                <div class="mt-4">
                                    @if ($aerodrome->aip_link != '')
                                        <a href="{{ $aerodrome->aip_link }}" target="_blank">
                                            <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">AIP
                                                <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-external-link fea icon-sm" style="margin-left: 10px; margin-top:-4px">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                            </button>
                                        </a>
                                    @else
                                        <a href="{{ route('pilots.aerodromes.charts', $aerodrome->icao) }}">
                                            <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">Charts</button>
                                        </a>
                                    @endif
                                    <a href="https://wiki.vatsim-germany.org/{{ strtoupper($aerodrome->icao) }}" target="_blank">
                                        <button type="button" class="btn btn-soft-primary mt-3" style="width: 90%; margin-left: 5%">Wiki
                                            <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-external-link fea icon-sm" style="margin-left: 10px; margin-top:-4px">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <!-- RECENT POST -->

                            <!-- RECENT POST -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0">
                                    Active ATC
                                </span>

                                <div class="mt-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1 ms-3 table-responsive" style="margin-right: 1rem !important;" id="table-atc-container">
                                            <table class="table table-center" id="table-active-atc">
                                                <thead>
                                                <tr>
                                                    <th class="text-center border-bottom fw-bold">@lang('pilot.aerodromes.aerodrome.station-table-header.0')</th>
                                                    <th class="text-center border-bottom fw-bold">@lang('pilot.aerodromes.aerodrome.station-table-header.1')</th>
                                                </tr>
                                                </thead>
                                                <tbody wire:ignore>
                                                <tr id="loading-text-atc">
                                                    <td class="text-center" colspan="2">Loading...</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RECENT POST -->
                        </div>
                    </div>
                </div>
                <!--end col-->
                <!-- END SIDEBAR -->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>


@push('scripts')
    @vite(['resources/ts/special/aerodrome.ts'])
@endpush
