<section class="section bg-secondary-100 dark:bg-secondary-800/40">
    <div class="site-container">
        <div class="mx-auto max-w-2xl text-center">
            <span class="landing-section-label">VATSIM Germany</span>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">@lang('landing.events.title')</h2>
            <p class="mt-4 text-secondary-600 dark:text-secondary-300">@lang('landing.events.text')</p>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="event-container"
            data-show-more="{{ __('pages.landing.show-more-events') }}"
            data-empty="{{ __('pages.landing.no-events') }}">
            @for ($i = 0; $i < 9; $i++)
                <article class="{{ $i > 5 ? 'hide' : '' }}" id="event-{{ $i }}">
                    <a href="javascript:void(0)" id="event-readmore-{{ $i }}" class="block h-full">
                        <div class="card landing-event-card">
                            <div class="relative overflow-hidden">
                                <div id="event-loader-{{ $i }}" class="event-loader-show absolute inset-0"></div>
                                <div class="card-img-top" id="event-banner-{{ $i }}"></div>
                            </div>
                            <div class="card-body">
                                <span class="badge mb-3 hidden" id="event-cpt-banner-{{ $i }}">Controller Practical Test</span>
                                <h3 class="text-lg font-semibold text-primary-900 dark:text-secondary-50" id="event-title-{{ $i }}">@lang('landing.events.loading-text')</h3>
                                <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-300" id="event-date-{{ $i }}"></p>
                            </div>
                        </div>
                    </a>
                </article>
            @endfor

            <div class="col-span-full mt-2 text-center" id="show-events-btn-container">
                <button type="button" class="btn btn-primary" id="show-events-btn" disabled>@lang('pages.landing.show-more-events')</button>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    @vite(['resources/ts/special/events.ts'])
@endpush
