<div>
    @component('components.layouts.content',[
        'header' => $aerodrome->name,
        'links' => [
            route('landing') => config('app.name'),
            'Pilots',
            route('pilots.aerodromes.viewall') => __('pilot.aerodromes.title'),
            $aerodrome->icao,
            ],
        'backgroundurl' => $aerodrome->background_image_url ?? iasset('images/profile/profile_1.png')
    ])
        <ul class="list-unstyled mt-4 mb-0">
            <li class="list-inline-item h4 user me-2 text-light" wire:ignore>
                <span class="badge rounded bg-soft-danger p-2" id="del_indicator"> DEL </span>
                <span class="badge rounded bg-soft-danger p-2" id="gnd_indicator"> GND </span>
                <span class="badge rounded bg-soft-danger p-2" id="twr_indicator"> TWR </span>
                <span class="badge rounded bg-soft-danger p-2" id="app_indicator"> APP </span>
                <span class="badge rounded bg-soft-danger p-2" id="ctr_indicator"> CTR </span>
            </li>
        </ul>
    @endcomponent

    <section class="section">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-8 col-md-6 mb-4">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <div class="card-body content">
                            <div class="w-100">
                                <div class="row" id="counter">
                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 text-primary">{{ $aerodrome->icao }}</h4>
                                            <h6 class="counter-head text-muted">ICAO</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 text-primary">
                                                {{ $aerodrome->iata ?? '-' }}
                                            </h4>
                                            <h6 class="counter-head text-muted">IATA</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 text-primary">{{ $aerodrome->elevation }}</h4>
                                            <h6 class="counter-head text-muted">Elevation (ft)</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>

                                    <div class="col-sm-3 col-6 pt-2">
                                        <div class="counter-box text-center">
                                            <h4 class="mb-0 text-primary">
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
                            <h4 class="text-dark">Stand Information</h4>
                            @vite('resources/scss/special/aerodrome-mapbox.scss')
                            @vite('resources/scss/special/leaflet.scss')
                            <div class="w-100" id="map-container">
                                <div wire:ignore id="map" class="w-100 mt-3 rounded" style="height: 500px">
                                    <h5 class="mt-3 text-muted" id="event-title">@lang('pilot.aerodromes.aerodrome.loading-event-text')</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card blog-detail border-0 shadow rounded mt-4">
                        <div class="card-body content">
                            <h4 class="text-dark">@lang('pilot.aerodromes.aerodrome.upcoming-event-title-text')</h4>
                            <div wire:ignore class="w-100" id="event-container">
                                <img src="" class="card-img-top loader-show overflow-hidden mt-3 w-100" id="event-banner" style="min-width: 100%">
                                <h5 class="mt-3 text-muted" id="event-title">@lang('pilot.aerodromes.aerodrome.loading-event-text')</h5>
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

                            <div class="widget">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0 text-dark">
                                    Links
                                </span>

                                <div class="mt-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1 ms-1">
                                            @foreach($links as $category)
                                                <span class="d-block text-center h6 mb-1 mt-2 text-dark">
                                                    {{ $category[0]->category }}
                                                </span>
                                                @foreach($category as $link)
                                                    @if(!preg_match("/^https:\/\/[a-zA-Z0-9_-]*\.?vatsim-germany\.org.*/", $link->url))
                                                        <a class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#warning-{{ bin2hex($link->url) }}">
                                                            {{ $link->name }}
                                                            <i data-feather="external-link" class="ms-1 fea icon-sm"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ $link->url }}" class="btn btn-secondary w-100 mb-2">
                                                            {{ $link->name }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- RECENT POST -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0 text-dark">
                                    METAR
                                </span>

                                <div class="mt-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1 ms-3" style="margin-right: 1rem !important;">
                                            <code wire:ignore class="d-block title text-dark" id="metar-container">Loading...</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RECENT POST -->
                            <!-- RECENT POST -->
                            <div class="widget mt-4" id="atis-widget" wire:ignore.self>
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0 text-dark">
                                    ATIS
                                </span>

                                <div class="mt-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1 ms-3" style="margin-right: 1rem !important;">
                                            <code wire:ignore class="d-block title text-dark" id="atis-container">Loading...</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- RECENT POST -->

                            <!-- RECENT POST -->
                            <div class="widget mt-4">
                                <span class="bg-light d-block py-2 rounded shadow text-center h6 mb-0 text-dark">
                                    Active ATC
                                </span>

                                <div class="mt-2">
                                    <div class="d-flex align-items-center">
                                        <div wire:ignore class="flex-1 ms-3 table-responsive" style="margin-right: 1rem !important;" id="table-atc-container">
                                            <table class="table table-center" id="table-active-atc">
                                                <tbody id="loading-text-atc">
                                                <tr>
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

        @foreach($links as $category)
            @foreach($category as $link)
                <div class="modal fade" id="warning-{{ bin2hex($link->url) }}" tabindex="-1" aria-labelledby="LoginForm-title" style="display: none;"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded shadow border-0">
                            <div class="modal-body text-center">
                                <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
                                     style="height: 80px; width:80px;">
                                    <i data-feather="alert-triangle" class=""></i>
                                </div>
                                <div class="mt-4">
                                    <h4>Weiterleitung</h4>
                                    <p class="text-muted">
                                        Du verlässt VATSIM Germany.
                                        Der von dir ausgewählte Link leitet dich auf eine externe Seite <span style="font-family: monospace">{{ $link->url }}</span>
                                        weiter.
                                        VATSIM Germany ist in keiner Weise mit dieser Seite verbunden und für keine Inhalte der Seite verantwortlich.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ $link->url }}" target="_blank" class="btn btn-primary">Verstanden</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

    </section>
</div>


@push('scripts')
    @vite(['resources/ts/special/aerodrome.ts'])
@endpush
