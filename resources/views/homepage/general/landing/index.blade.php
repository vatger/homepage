@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-260 bg-primary d-table w-100" id="hero-section"
        style="background: url('{{ asset('images/hero-banners/hero_' . rand(1, 9) . '.png') }}') center center; background-size: cover">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-lg-6 col-md-12">
                    <div class="title-heading mt-4 text-center text-lg-start">
                        <h1 class="heading mb-3 title-dark text-white">VATSIM Germany</h1>
                        <p class="para-desc text-white-50">Controlling The Virtual German Airspace With Passion!</p>
                        <div class="mt-4">
                            <a href="{{ route('getting-started') }}" class="btn btn-primary">@lang('landing.join-now-button-content')</a>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M720 125L2160 0H2880V250H0V125H720Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- Hero End -->

    {{-- Partners --}}
    <section class="section pt-0 pb-4">
        <div class="container mt-100 mt-60">
            <div class="row align-items-center pb-5 @if (\App\Models\Partner::all()->count() > 0) border-bottom @endif">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="position-relative">
                        <img class="rounded img-fluid mx-auto d-block bg-light" src="{{ asset('images/pilots/aerodromes_2.png') }}" alt="">
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-7 col-md-6 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                    <div class="ms-lg-5 ms-md-4">
                        <div class="section-title">
                            <span class="badge rounded-pill bg-soft-primary">@lang('landing.welcome.badge-text')</span>
                            <h4 class="title mt-3 mb-4">@lang('landing.welcome.title')</h4>
                            <p class="text-muted para-desc mx-auto">@lang('landing.welcome.text.0')</p>
                            <div class="mt-4">
                                <a href="javascript:void(0)" class="btn btn-pills btn-soft-primary">@lang('landing.read-more-button-content')</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </section>

    @if ($partners->count() > 0)
        <section class="section mt-0 pt-3 pb-5 mb-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="section-title mb-4 pb-2">
                            <h4 class="title mb-1">Unsere Partner</h4>
                            <p class="text-muted mb-0 pb-0">Mehr Informationen könnt ihr <a href="#" class="link">hier</a> sammeln.</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="row">
                    <div class="col-12 mt-0">
                        <div
                            class="@if ($partners->count() == 1) tiny-one-item @elseif($partners->count() == 2) tiny-two-item @else tiny-three-item @endif">
                            @foreach ($partners as $partner)
                                <a href="https://google.de">
                                    <div class="tiny-slide text-center">
                                        <div class="client-testi rounded shadow m-2 p-4">
                                            <img src="{{ $partner->logo_url }}" class="img-fluid avatar avatar-small"
                                                style="max-height: 65px; width: auto;" alt="">
                                            <p class="text-start mt-3 mb-0 text-dark"><strong>{{ $partner->name }}</strong></p>
                                            <div class="text-muted text-start" id="description-text">
                                                @if (strlen($partner->description) > 40)
                                                    {!! substr($partner->description, 0, 40) . '...' !!}
                                                @else
                                                    {!! $partner->description !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end container-->
        </section>
        <!--end section-->

        <style>
            .client-testi {
                cursor: pointer !important;
            }

            #description-text>p {
                padding-bottom: 0 !important;
                margin-bottom: 0 !important;
            }
        </style>
    @endif

    {{-- Events --}}
    <!-- Section Start -->
    <section class="section pt-md-5 pt-5 bg-light">
        <!-- Start Features -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">@lang('landing.events.title')</h4>
                        <p class="text-muted para-desc mx-auto mb-0">@lang('landing.events.text')</p>

                        <div class="alert alert-danger mt-5" role="alert" id="danger-alert-event" style="display: none; width: 60%; margin-left: 20%">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row" id="event-container">

                @for ($i = 0; $i < 9; $i++)
                    <div class="col-lg-4 col-md-6 mb-4 pb-2 @if ($i > 5) hide @endif" id="event-{{ $i }}">
                        <a href="javascript:void(0)" id="event-readmore-{{ $i }}">
                            <div class="card blog rounded border-0 shadow overflow-hidden">
                                <div class="position-relative">
                                    <div style="width: 100%; height: 100%; position: absolute" id="event-loader-{{ $i }}" class="loader-show">
                                    </div>
                                    <div class="overlay rounded-top"></div>
                                    <div class="card-img-top loader-show overflow-hidden" id="event-banner-{{ $i }}"
                                        style="min-height: 200px; min-width: 356px"></div>
                                </div>
                                <div class="card-body content">
                                    <span class="badge rounded-pill bg-soft-primary mb-2" id="event-cpt-banner-{{ $i }}"
                                        style="display: none">Controller Practical
                                        Test</span>
                                    <h5>
                                        <span class="card-title title text-dark" id="event-title-{{ $i }}">@lang('landing.events.loading-text')
                                        </span>
                                    </h5>
                                    <div class="post-meta d-flex justify-content-between mt-3">
                                        <ul class="list-unstyled mb-0">
                                            <li class="list-inline-item me-2 mb-0">
                                                <span href="javascript:void(0)" class="text-muted" id="event-date-{{ $i }}">
                                                    <i class="uil uil-heart me-1"></i>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--end col-->
                @endfor

                <div style="text-align: center" class="mt-4 mb-0 pb-0" id="show-events-btn-container">
                    <button type="button" class="btn btn-pills btn-soft-primary" id="show-events-btn" disabled> Show More</button>
                </div>
            </div>
            <!-- End Features -->
        </div>
    </section>
    <!--end section-->
    <!-- section End -->

    <!-- Section Start -->
    <section class="section pt-md-5 pt-5">
        <!-- Start Features -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-4">@lang('booking.atc.title')</h4>
                        <p class="text-muted para-desc mx-auto mb-0">@lang('booking.atc.text.landing')</p>

                        <div class="alert alert-danger mt-5" role="alert" id="danger-alert-event" style="display: none; width: 60%; margin-left: 20%">
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row pt-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr id="bookingCalendarHeader">
                                </tr>
                            </thead>
                            <tbody id="bookingCalendarBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!-- End Features -->
        </div>
    </section>
    <!--end section-->
    <!-- section End -->

    <style>
        @keyframes load {
            0% {
                margin-left: -100%;
            }

            100% {
                margin-left: 100%;
            }
        }

        .loader-show {
            transition: opacity 0.5s;
        }

        .loader-show::before {
            content: '';
            display: block;
            height: 100%;
            min-height: 200px;
            width: 100%;

            @auth @if (\Auth::user()->settings->dark_mode)
                    background: linear-gradient(to right, transparent 0%, rgb(64 64 64 / 39%) 50%, transparent 100%);
                @else
                    background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
                @endif
            @else
                background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
            @endauth
            animation: 1.5s ease-in-out 0s infinite normal none running;
            animation-name: load;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function excecutePageLoad() {
            let g_eventCount = -1;

            $(function() {
                let index = 0;

                $.ajax({
                    url: config.routes.api.events.loadEvents,
                    type: 'GET',
                    dataType: 'json',
                    success: (data) => {
                        g_eventCount = data.length;
                        $.each(data, (key, data) => {
                            // Load variables for easier access
                            let eventLoader = $(`#event-loader-${key}`);
                            let eventBanner = $("#event-banner-" +
                            key); // Note: Before replacing, this is simply a size-placeholder
                            let eventTitle = $("#event-title-" + key);
                            let eventDate = $("#event-date-" + key);
                            let eventReadMore = $("#event-readmore-" + key);
                            let eventBannerParent = eventBanner.parent();

                            // Remove loading-animation and append banner image
                            eventBanner.remove();
                            eventBannerParent.append(
                                `<img alt="" src="${data.banner}" class="card-img-top overflow-hidden" id="event-banner-${index}" style="min-height: 200px; min-width: 356px">`
                                );

                            // Add event-specific context (name, date, etc.)
                            eventTitle.text(data.name);
                            eventDate.text(formatDate(new Date(data.start_time)) + "Z");
                            eventLoader.remove();
                            eventReadMore.css("display", "block");
                            eventReadMore.attr('href', '/events/view/' + data.id)

                            if (data['type'] !== 'Event')
                                $("#event-cpt-banner-" + key).css('display', 'inline-block');

                            // Enable the "show more events" button
                            $("#show-events-btn").attr('disabled', false);

                            index++;
                        });

                        if (index < 9) {
                            for (let i = index; i < 9; i++) {
                                let eventContainer = $("#event-" + i);
                                eventContainer.remove();
                            }
                        }
                    },
                    error: () => {
                        for (let i = 0; i < 9; i++) {
                            let eventContainer = $("#event-" + i);
                            eventContainer.remove();
                        }

                        let errorContainer = $("#danger-alert-event");
                        errorContainer.text("@lang('landing.events.loading-error-text')");
                        errorContainer.css("display", "block");
                    }
                });

                function formatDate(date) {
                    let d = new Date(date),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear(),
                        hour = '' + d.getUTCHours(),
                        min = '' + d.getUTCMinutes();


                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;
                    if (hour.length < 2)
                        hour = '0' + hour;
                    if (min.length < 2)
                        min = '0' + min;

                    return [day, month, year].join('.') + ", " + [hour, min].join(':');
                }
            });

            $(function() {
                $("#show-events-btn").on('click', function() {
                    $(this).remove();
                    if (g_eventCount !== -1 && g_eventCount < 7) {
                        $("#show-events-btn-container").append(`
                                <div class="alert alert-danger mt-3" role="alert">No further events found (lang)</div>
                            `);
                        return;
                    }

                    for (let i = 0; i < 3; i++) {
                        $(`#event-${i+6}`).removeClass('hide');
                    }
                });
            });

            function randomHsl() {
                return 'hsl(' + Math.random() * 360 + ', 32%, 16%)';
            }

            $(document).ready(function() {
                let bookingApiEndpoint =
                    '{{ route('api.booking.atc', ['start' => \Carbon\Carbon::now()->utc()->format('Y-m-d'),'end' => \Carbon\Carbon::now()->utc()->addDays(4)->format('Y-m-d')]) }}';
                $.ajax({
                    type: 'GET',
                    url: bookingApiEndpoint,
                    success: function(data) {
                        $('#bookingCalendarHeader').html(
                            '<th style="width: 16.66%">Aerodrome / Station</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(1)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(2)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(3)->format('Y-m-d') }}</th><th style="width: 16.66%">{{ \Carbon\Carbon::now()->utc()->addDays(4)->format('Y-m-d') }}</th>'
                        );
                        let calendarBody = '';
                        for (i = 0; i < data.length; i++) {
                            calendarBody +=
                                '<tr><td colspan="6" class="text-start" style="background-color: rgba(47, 85, 212, 0.05) !important;">' +
                                data[i].name + '<br>' + data[i].icao + '</td></tr>';
                            for (j = 0; j < data[i].stations.length; j++) {
                                if (data[i].stations[j].bookings !== undefined && data[i].stations[j].bookings
                                    .length > 0) {
                                    calendarBody += '<tr><td>' + data[i].stations[j].name + '<br>' + data[i]
                                        .stations[j].ident + '</td>';
                                    // Evaluate the bookings and move them to correct columns
                                    let bookings = data[i].stations[j].bookings;
                                    let today = DateTime.now().setZone('utc');
                                    for (day = 0; day < 5; day++) {
                                        let date = today.plus({
                                            days: day
                                        });
                                        // Compare bookings to the days and attach to column if required
                                        calendarBody += '<td><div class="row">';
                                        for (x = 0; x < bookings.length; x++) {
                                            let startDay = DateTime.fromISO(bookings[x].starts_at);
                                            if (date.hasSame(startDay, 'day')) {
                                                calendarBody +=
                                                    '<div class="col-12" style="background-color: ' +
                                                    randomHsl() + '; color: #fff;">' + bookings[x].startTime +
                                                    ' - ' + bookings[x].endTime;
                                                @if (Auth::check())
                                                    calendarBody += '<br>' + bookings[x].controller.username;
                                                @endif
                                                calendarBody += '</div>';
                                            }
                                        }
                                        calendarBody += '</div></td>';
                                    }
                                    // Close the row
                                    calendarBody += '</tr>';
                                }
                            }
                        }
                        $('#bookingCalendarBody').html(calendarBody);
                    }
                });
            });
        }

        deferLoading(excecutePageLoad);
    </script>
@endpush
