<div>
    @component('components.layouts.content',[
        'header' => $aerodrome->name,
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.piloten.titel'),
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

    <section class="section aerodrome-page">
        <div class="container">
            <div class="row g-4">
                <!-- BLog Start -->
                <div class="col-lg-9 col-md-6">
                    <div class="card blog blog-detail aerodrome-panel">
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
                                            <h6 class="counter-head text-muted">@lang('pages.aerodrome.elevation')</h6>
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
                                            <h6 class="counter-head text-muted">@lang('pages.aerodrome.civil')</h6>
                                        </div>
                                        <!--end counter box-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card blog blog-detail aerodrome-panel mt-4">
                        <div class="card-body content">
                            <h4 class="text-dark">@lang('pages.aerodrome.stand-information')</h4>
                            @vite('resources/scss/special/aerodrome-mapbox.scss')
                            @vite('resources/scss/special/leaflet.scss')
                            <div class="w-100" id="map-container">
                                <div wire:ignore id="map" class="w-100 mt-3 rounded" style="height: 500px">
                                    <h5 class="mt-3 text-muted" id="event-title">@lang('pilot.aerodromes.aerodrome.loading-event-text')</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 lg:grid-cols-[minmax(0,.8fr)_minmax(0,1.2fr)]">
                        <section class="surface overflow-hidden">
                            <h2 class="aerodrome-dynamic-title">METAR</h2>
                            <div class="p-5">
                                <code wire:ignore class="aerodrome-weather" id="metar-container">@lang('pages.common.loading')</code>
                            </div>
                        </section>

                        <section class="surface overflow-hidden" id="atis-widget" wire:ignore.self>
                            <h2 class="aerodrome-dynamic-title">ATIS</h2>
                            <div class="p-5">
                                <code wire:ignore class="aerodrome-weather" id="atis-container"
                                      data-empty-text="{{ __('pages.aerodrome.no-atis-available') }}">@lang('pages.common.loading')</code>
                            </div>
                        </section>

                        <section class="surface overflow-hidden lg:col-span-2">
                            <h2 class="aerodrome-dynamic-title">@lang('pages.aerodrome.active-atc')</h2>
                            <div wire:ignore class="table-responsive" id="table-atc-container"
                                 data-monitoring-text="{{ __('pages.aerodrome.monitoring') }}"
                                 data-empty-text="{{ __('pages.aerodrome.no-atc-online') }}">
                                <table class="table aerodrome-atc-table" id="table-active-atc">
                                    <tbody id="loading-text-atc">
                                    <tr>
                                        <td class="text-center" colspan="2">@lang('pages.common.loading')</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section class="surface overflow-hidden lg:col-span-2">
                            <div class="aerodrome-dynamic-title flex items-center justify-between gap-4">
                                <h2 class="text-base font-bold text-primary-900 dark:text-secondary-50">@lang('pages.aerodrome.aircraft')</h2>
                                <span class="badge" id="aircraft-count">0</span>
                            </div>
                            <div wire:ignore class="aerodrome-aircraft-list" id="aircraft-container"
                                 data-empty-text="{{ __('pages.aerodrome.no-aircraft') }}"
                                 data-summary-template="{{ __('pages.aerodrome.aircraft-summary') }}">
                                <p class="p-5 text-sm text-secondary-500 dark:text-secondary-300">@lang('pages.common.loading')</p>
                            </div>
                        </section>
                    </div>
                </div>
                <!-- BLog End -->

                <!-- START SIDEBAR -->
                <div class="col-lg-3 col-md-6 col-12">
                    <aside class="aerodrome-sidebar surface overflow-hidden">
                        <section class="aerodrome-sidebar-section">
                            <h2 class="aerodrome-sidebar-title">@lang('pages.aerodrome.links')</h2>
                            <div class="space-y-5 p-4">
                                @foreach($links as $category)
                                    <div>
                                        <h3 class="mb-2 px-1 text-sm font-bold text-primary-900 dark:text-secondary-50">{{ $category[0]->category }}</h3>
                                        <div class="space-y-2">
                                            @foreach($category as $link)
                                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="aerodrome-sidebar-link">
                                                    <span>{{ $link->name }}</span>
                                                    <i data-feather="external-link" class="size-4 shrink-0" aria-hidden="true"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="aerodrome-sidebar-section">
                            <h2 class="aerodrome-sidebar-title">@lang('pilot.aerodromes.aerodrome.upcoming-event-title-text')</h2>
                            <div wire:ignore class="aerodrome-sidebar-events p-4" id="event-container">
                                <div class="animate-pulse space-y-3">
                                    <div class="h-32 rounded-2xl bg-secondary-100 dark:bg-secondary-700"></div>
                                    <div class="h-4 w-3/4 rounded bg-secondary-100 dark:bg-secondary-700"></div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
                <!--end col-->
                <!-- END SIDEBAR -->
            </div>
            <!--end row-->
        </div>
        <!--end container-->

        @foreach($links as $category)
            @foreach($category as $link)
                <div class="modal fade aerodrome-link-modal" id="warning-{{ bin2hex($link->url) }}" tabindex="-1"
                     aria-labelledby="warning-title-{{ bin2hex($link->url) }}" style="display: none;"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded shadow border-0">
                            <div class="modal-header border-0 pb-0">
                                <h4 class="modal-title" id="warning-title-{{ bin2hex($link->url) }}">
                                    @lang('general.external-link.title')
                                </h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="{{ __('general.phrases.cancel') }}"></button>
                            </div>
                            <div class="modal-body text-center pt-3">
                                <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
                                     style="height: 80px; width:80px;">
                                    <i data-feather="alert-triangle" class=""></i>
                                </div>
                                <div class="mt-4">
                                    <p class="mb-2">@lang('general.external-link.text')</p>
                                    <code class="d-block text-break">{{ $link->url }}</code>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    @lang('general.phrases.cancel')
                                </button>
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-primary" data-bs-dismiss="modal">
                                    @lang('general.external-link.continue')
                                </a>
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
