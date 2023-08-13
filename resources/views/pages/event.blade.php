@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-260 bg-primary d-table w-100" id="hero-section"
             style="background: url('{{ $event->banner }}') no-repeat center center fixed; background-size: cover; padding-top: 340px !important;">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 85%)"></div>
        <div class="container">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-12 text-center">
                    <div class="title-heading text-center">
                        <h1 class="heading title-dark text-white" style="font-size: 52px !important; letter-spacing: 3px !important; margin-top: -50px">
                            {{ $event->name }}</h1>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </section>
    <!--end section-->
    <!-- Hero End -->

    <section class="section pt-0">
        <div class="container mt-100">
            <div class="row align-items-start">
                <div class="col-lg-6 col-md-12 col-sm-12 mt-4 mt-sm-0 pt-2 pt-sm-0 order-1 order-sm-1 order-md-1 order-lg-0">
                    <div class="ms-lg-5 ms-md-4">
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
                                <h6 class="text-muted mb-3 p-1"><strong>Suggested Route(s)</strong>:</h6>
                                @foreach ($event->routes as $rte)
                                    <p class="text-muted px-1 @if ($loop->index > 0) border-top pt-3 @endif">
                                        <strong>{{ $rte->departure }} - {{ $rte->arrival }}</strong>: {{ $rte->route }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!--end col-->
                <div class="col-lg-6 col-md-12 col-sm-12 mt-4 mt-sm-0 pt-2 mb-4 pt-sm-0 order-0 order-sm-0 order-md-0 order-lg-1">
                    <div class="position-relative p-md-4 p-lg-4">
                        <img class="rounded img-fluid mx-auto d-block bg-light mt-lg-4" src="{{ $event->banner }}" alt="">
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </section>
@endsection
