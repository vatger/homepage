@props(['traffic'])

<section class="section">
    <div class="site-container">
        <div class="mb-10 max-w-2xl lg:mb-12">
            <span class="landing-section-label">@lang('landing.traffic.label')</span>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-primary-900 dark:text-secondary-50 sm:text-4xl">@lang('landing.traffic.title')</h2>
            <p class="mt-3 text-secondary-600 dark:text-secondary-300">@lang('landing.traffic.text')</p>
            <p class="mt-5 flex flex-wrap gap-2 text-sm font-semibold text-primary-700 dark:text-secondary-100">
                <span class="landing-traffic-stat">@lang('landing.traffic.departures-count', ['count' => $traffic['summary']['departures']])</span>
                <span class="landing-traffic-stat">@lang('landing.traffic.arrivals-count', ['count' => $traffic['summary']['arrivals']])</span>
                <span class="landing-traffic-stat">@lang('landing.traffic.controllers-count', ['count' => $traffic['summary']['controllers']])</span>
                <span class="landing-traffic-stat">@lang('landing.traffic.atis-count', ['count' => $traffic['summary']['atis']])</span>
            </p>
        </div>

        <div class="grid items-start gap-6 md:grid-cols-2 xl:items-stretch xl:grid-cols-[minmax(20rem,1.15fr)_minmax(16rem,.78fr)_minmax(16rem,.78fr)] xl:gap-8">
            <div class="landing-traffic-map">
                <img src="{{ route('landing.traffic-map') }}" alt="@lang('landing.traffic.map-alt')">
            </div>

            <section class="landing-traffic-panel">
                <div class="landing-traffic-panel-heading">
                    <h3>@lang('landing.traffic.aerodromes')</h3>
                </div>
                @if(count($traffic['aerodromes']) > 0)
                    <ul>
                        @foreach($traffic['aerodromes'] as $aerodrome)
                            <li>
                                <a href="{{ route('pilots.aerodromes.view', ['icao' => $aerodrome['icao']]) }}" class="landing-traffic-row">
                                    <span class="min-w-0">
                                        <span class="block font-semibold text-primary-900 dark:text-secondary-50">{{ $aerodrome['icao'] }}</span>
                                        <span class="block truncate text-xs text-secondary-500 dark:text-secondary-300">{{ $aerodrome['name'] }}</span>
                                    </span>
                                    <span class="flex shrink-0 items-center gap-2">
                                        @if($aerodrome['departures'] > 0)
                                            <span class="landing-traffic-stat" title="@lang('landing.traffic.departures')">&uarr; {{ $aerodrome['departures'] }}</span>
                                        @endif
                                        @if($aerodrome['arrivals'] > 0)
                                            <span class="landing-traffic-stat" title="@lang('landing.traffic.arrivals')">&darr; {{ $aerodrome['arrivals'] }}</span>
                                        @endif
                                        @if($aerodrome['controllers'] > 0)
                                            <span class="landing-traffic-atc">ATC {{ $aerodrome['controllers'] }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-5 py-6 text-sm text-secondary-500 dark:text-secondary-300">@lang('landing.traffic.empty-aerodromes')</p>
                @endif
            </section>

            <section class="landing-traffic-panel">
                <div class="landing-traffic-panel-heading">
                    <h3>@lang('landing.traffic.stations')</h3>
                </div>
                @if(count($traffic['stations']) > 0)
                    <ul @class(['landing-traffic-list-scroll' => count($traffic['stations']) > 7])>
                        @foreach($traffic['stations'] as $station)
                            <li class="landing-traffic-row">
                                <span class="min-w-0">
                                    <span class="block truncate font-semibold text-primary-900 dark:text-secondary-50">{{ $station['callsign'] }}</span>
                                    <span class="block truncate text-xs text-secondary-500 dark:text-secondary-300">{{ $station['name'] }}</span>
                                </span>
                                <span class="landing-traffic-stat shrink-0">{{ $station['frequency'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-5 py-6 text-sm text-secondary-500 dark:text-secondary-300">@lang('landing.traffic.empty-stations')</p>
                @endif
            </section>
        </div>
    </div>
</section>
