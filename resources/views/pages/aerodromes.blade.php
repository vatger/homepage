<div>
    @component('components.layouts.content',[
        'header' => __('pilot.aerodromes.title'),
        'links' => [
            route('landing') => config('app.name'),
            __('navigation.piloten.titel'),
            route('pilots.aerodromes.viewall') => __('pilot.aerodromes.title')
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 mt-4 col-12">
                    <div class="rounded-2xl border border-secondary-200 bg-white p-4 shadow-sm dark:border-secondary-700 dark:bg-secondary-800">
                        <div class="card-body p-0 content">
                            <div class="mb-3">
                                <label class="form-label">@lang('pilot.aerodromes.search-text')</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="book" class="fea icon-sm icons"></i>
                                    <input wire:model.live="search" name="subject" class="form-control ps-5" type="text"
                                           placeholder="@lang('pilot.aerodromes.search-input-placeholder')">
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                        <p class="text-muted mb-0" style="display: none" id="search-count-container">
                            {!! __('pages.aerodromes.search-results', ['count' => '<span id="search-count"></span>']) !!}
                        </p>
                    </div>
                </div>
                <!--end col-->
            </div>

            <div class="row mb-1 text-center">
                <div class="row" style="padding-right: 0 !important; left: 5px !important;">
                    @foreach($aerodromes as $aerodrome)
                        @php($summary = $aerodrome_summaries[$aerodrome->id] ?? ['roles' => [], 'departures' => 0, 'arrivals' => 0])
                        <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item" role="button">
                            <a wire:click="aerodrome_select({{ $aerodrome->id }})"
                               class="block cursor-pointer rounded-2xl border border-secondary-200 bg-white p-6 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-accent-500 hover:bg-secondary-50 hover:shadow-md dark:border-secondary-700 dark:bg-secondary-800 dark:hover:bg-secondary-700/70"
                               wire:loading.attr="aria-busy">
                                <h5 class="text-lg font-bold text-primary-900 dark:text-secondary-50">
                                    {{ $aerodrome->icao }}{{ $aerodrome->iata ? ' | ' . $aerodrome->iata : '' }}
                                </h5>
                                <p class="mt-1 text-sm text-secondary-600 dark:text-secondary-300">{{ $aerodrome->name }}</p>
                                @if(in_array(true, $summary['roles'], true) || $summary['departures'] > 0 || $summary['arrivals'] > 0)
                                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-secondary-200 pt-4 text-xs font-semibold dark:border-secondary-700">
                                        @foreach($summary['roles'] as $role => $online)
                                            @if($online)
                                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-200">{{ $role }}</span>
                                            @endif
                                        @endforeach
                                        @if($summary['departures'] > 0)
                                            <span class="inline-flex items-center gap-1 text-secondary-600 dark:text-secondary-300" title="@lang('pilot.aerodromes.departures')">
                                                <i data-feather="arrow-up-right" class="size-3.5" aria-hidden="true"></i>{{ $summary['departures'] }}
                                            </span>
                                        @endif
                                        @if($summary['arrivals'] > 0)
                                            <span class="inline-flex items-center gap-1 text-secondary-600 dark:text-secondary-300" title="@lang('pilot.aerodromes.arrivals')">
                                                <i data-feather="arrow-down-left" class="size-3.5" aria-hidden="true"></i>{{ $summary['arrivals'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        </div><!--end col-->
                    @endforeach
                </div>
                <div class="row justify-content-center">
                    {{ $aerodromes->links() }}
                </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
