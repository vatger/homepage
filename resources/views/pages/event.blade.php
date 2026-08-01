@extends('layouts.master')

@section('content')

    @component('components.layouts.content',[
           'header' => $event->name,
           'links' => [
               route('landing') => config('app.name'),
           __('pages.common.events'),
          $event->name
           ],
           'backgroundurl' => $event->banner,
           'compact' => true,
       ])
    @endcomponent

    <section class="pb-16 pt-12 sm:pb-20 sm:pt-14">
        <div class="site-container">
            <div class="grid items-start gap-8 lg:grid-cols-2 lg:gap-12">
                <div class="order-2 lg:order-1">
                    <div>
                        <div class="section-title">
                            @foreach ($event->airports as $apt)
                                @if (\App\Models\Navigation\Aerodrome::query()->isDe()->where('icao', $apt->icao)->exists())
                                    <a href="{{ route('pilots.aerodromes.view', ['icao' => $apt->icao]) }}">
                                        <span class="badge rounded-pill bg-soft-primary">{{ $apt->icao }}</span>
                                    </a>
                                @else
                                    <span class="badge rounded-pill bg-soft-primary">{{ $apt->icao }}</span>
                                @endif
                            @endforeach
                            <h4 class="title mt-3 mb-2">{{ $event->name }}</h4>
                            <p class="para-desc text-muted mb-4">{{ \Carbon\Carbon::parse($event->start_time)->format('d.m.Y H:i') }}Z
                                @if ($event->end_time)
                                    - {{ \Carbon\Carbon::parse($event->end_time)->format('d.m.Y H:i') }}Z
                                @endif
                            </p>
                            <p class="para-desc">{!! nl2br($event->description) !!}</p>
                        </div>

                        @if ($event->routes)
                            <div class="alert alert-light shadow mt-4" id="event-routes" role="alert" style="display: block;">
                                <h6 class="text-muted mb-3 p-1"><strong>@lang('pages.event.suggested-routes')</strong>:</h6>
                                @foreach ($event->routes as $rte)
                                    <p class="text-muted px-1 @if ($loop->index > 0) border-top pt-3 @endif">
                                        <strong>{{ $rte->departure }} - {{ $rte->arrival }}</strong>: {{ $rte->route }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <div class="flex justify-center lg:justify-end">
                        <img class="max-h-96 w-auto max-w-full rounded-2xl bg-secondary-100 object-contain shadow-sm dark:bg-secondary-800"
                             src="{{ $event->banner }}" alt="{{ $event->name }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
