@extends('homepage.pilots.aerodromes.partial.hero')

@section('hero-img-src')
    @if (file_exists(public_path('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg')))
        {{ asset('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg') }}@else{{ asset('images/pilots/aerodromes_' . rand(1, 2) . '.png') }}
    @endif
@endsection

@section('title')
    {{ $aerodrome->name }}
@endsection

@section('subtitle')
    <ul class="list-unstyled mt-4 mb-0">
        <li class="list-inline-item h4 user me-2 text-light">
            <span class="badge rounded bg-soft-danger p-2" id="del_indicator"> DEL </span>
            <span class="badge rounded bg-soft-danger p-2" id="gnd_indicator"> GND </span>
            <span class="badge rounded bg-soft-danger p-2" id="twr_indicator"> TWR </span>
            <span class="badge rounded bg-soft-danger p-2" id="app_indicator"> APP </span>
            <span class="badge rounded bg-soft-danger p-2" id="ctr_indicator"> CTR </span>
        </li>
    </ul>
@endsection

@section('breadcrumb')
    @lang('pilot.aerodromes.aerodrome.breadcrumb.0') <li class='breadcrumb-item active'>{{ $aerodrome->icao }}</li>
@endsection

@section('aerodrome-content')
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
                                                @if ($aerodrome->iata)
                                                    {{ $aerodrome->iata }}
                                                @else
                                                    -
                                                @endif
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
                            <div class="w-100" id="map-container">
                                <div id="map" class="w-100 mt-3 rounded" style="height: 500px"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card blog blog-detail border-0 shadow rounded mt-4">
                        <div class="card-body content">
                            <h4>@lang('pilot.aerodromes.aerodrome.upcoming-event-title-text')</h4>
                            <div class="w-100" id="event-container">
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
                                            <a class="d-block title text-dark" id="metar-container">Loading...</a>
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
                                            <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">AIP <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-external-link fea icon-sm" style="margin-left: 10px; margin-top:-4px">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg></button>
                                        </a>
                                    @else
                                        <a href="{{ route('pilots.aerodromes.charts', $aerodrome->icao) }}">
                                            <button type="button" class="btn btn-soft-primary" style="width: 90%; margin-left: 5%">Charts</button>
                                        </a>
                                    @endif
                                    <a href="https://wiki.vatsim-germany.org/{{ strtoupper($aerodrome->icao) }}" target="_blank">
                                        <button type="button" class="btn btn-soft-primary mt-3" style="width: 90%; margin-left: 5%">Wiki <svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-external-link fea icon-sm" style="margin-left: 10px; margin-top:-4px">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg></button>
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
                                                <tbody>
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
            min-height: 350px;
            width: 100%;
            {{-- prettier-ignore-start --}}
			@if (\Auth::check() && \Auth::user()->settings->dark_mode)background: linear-gradient(to right, transparent 0%, rgb(64 64 64 / 39%) 50%, transparent 100%);
		    @else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
			@endif animation: 1.5s ease-in-out 0s infinite normal none running;{{-- prettier-ignore-end --}} animation-name: load;
        }

        .marker-free {
            background-color: rgba(0, 128, 0, 0.5);
            border: 1px solid rgba(0, 128, 0, 0.8);
            background-size: cover;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            cursor: pointer;
        }

        .marker-occupied {
            background-color: rgba(204, 7, 7, 0.5);
            border: 1px solid rgba(204, 7, 7, 0.8);
            background-size: cover;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            cursor: pointer;
        }

        .mapboxgl-popup {
            max-width: 200px;
        }

        .mapboxgl-popup-content {
            text-align: center;
            color: black;
            font-family: 'Open Sans', sans-serif;
            background-color: rgba(239, 239, 239, 0.92);
        }

        .mapboxgl-popup-tip {
            border-top-color: rgba(255, 255, 255, 0.92) !important;
        }

        .mapboxgl-popup-close-button {
            display: none !important;
        }
    </style>
@endsection

@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet">
@endpush

@push('custom-script')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>

    <!-- Load Map Data -->
    <script>
        $(document).ready(() => {
            let styleUrl = "";

            @if (Auth::check() && Auth::user()->settings->dark_mode)
                styleUrl = "mapbox://styles/nikki2048/ckyg12wrq5h6b15pcb4b4dev1";
            @else
                styleUrl = "mapbox://styles/nikki2048/ckyg6998m2ec515o86wkmkjnn";
            @endif

            mapboxgl.accessToken =
                'pk.eyJ1Ijoibmlra2kyMDQ4IiwiYSI6ImNrOXpibmR5bTA1MTIzZnJ0aXh1cG4yNjYifQ.b-1gEcULFsxkvP2s9BCXQg';
            const map = new mapboxgl.Map({
                container: 'map', // container ID
                style: styleUrl, // style URL
                center: [{{ $aerodrome->longitude }},
                    {{ $aerodrome->latitude }}
                ], // starting position [lng, lat]
                zoom: 12 // starting zoom
            });

            map.on('zoom', () => {
                $(".marker-occupied").css('width', 12 * ((map.getZoom() - 3) / 10));
                $(".marker-occupied").css('height', 12 * ((map.getZoom() - 3) / 10));
                $(".marker-free").css('width', 12 * ((map.getZoom() - 3) / 10));
                $(".marker-free").css('height', 12 * ((map.getZoom() - 3) / 10));
            });

            $.ajax({
                url: '{{ route('api.aerodrome.standstatus', ['icao' => $aerodrome->icao]) }}',
                type: 'GET',
                success: (data) => {

                    if (data.length == 0) {
                        $("#map-container").empty();
                        $("#map-container").append(
                            `<div class="alert alert-danger mt-3 text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.error-stand-load-nostand') </div>`
                        );
                        return;
                    }

                    $.each(data, (key, value) => {
                        const el = document.createElement('div');
                        if (value['occupier'] == null)
                            el.className = 'marker-free';
                        else
                            el.className = 'marker-occupied';

                        let callsign = "";
                        if (value['occupier'] != null) {
                            callsign = `<p class="pb-0 mb-0">${value['occupier']}</p>`
                        }

                        new mapboxgl.Marker(el)
                            .setLngLat([value['longitude'], value['latitude']])
                            .setPopup(
                                new mapboxgl.Popup({
                                    offset: 8
                                }) // add popups
                                .setHTML(
                                    `<p class="pb-0 mb-0" style="font-size: 15px"><strong>${value['id']}</strong></p>` +
                                    callsign
                                )
                            )
                            .addTo(map);
                    });
                },
                error: () => {
                    $("#map-container").empty();
                    $("#map-container").append(
                        `<div class="alert alert-danger mt-3 text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.error-stand-load-text') </div>`
                    );
                    return;
                }

            })
        });
    </script>

    <!-- Load Metar -->
    <script>
        $(document).ready(() => {
            $.ajax({
                url: '{{ route('api.loadMetar') }}',
                type: 'GET',
                data: {
                    icao: '{{ $aerodrome->icao }}'
                },
                success: (data) => {
                    if (data == "") {
                        $("#metar-container").empty();
                        $("#metar-container").append(
                            `<div class="alert alert-danger text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.error-metar-load-text') </div>`
                        );
                        return;
                    }

                    $("#metar-container").text(data);
                },
                error: (xhr, http, data) => {
                    $("#metar-container").empty();
                    $("#metar-container").append(
                        `<div class="alert alert-danger text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.error-metar-load-text') </div>`
                    );
                }
            });
        });
    </script>

    <!-- Load upcoming Event -->
    <script>
        $(document).ready(() => {
            $.ajax({
                url: '{{ route('api.loadEvent') }}',
                type: 'GET',
                data: {
                    icao: '{{ $aerodrome->icao }}'
                },
                success: (data) => {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        $("#event-container").empty();
                        $("#event-container").append(`
                        <div class="alert alert-danger mt-3 text-center" role="alert">@lang('pilot.aerodromes.aerodrome.loading-event-failed-content.0')</div>
                        `);

                        return;
                    }

                    if (data.length == 0) {
                        $("#event-container").empty();
                        $("#event-container").append(`
                        <div class="alert alert-danger mt-3 text-center" role="alert">@lang('pilot.aerodromes.aerodrome.loading-event-failed-content.1')</div>
                        `);
                        return;
                    }
                    $("#event-banner").prop('src', data[0]['banner']);
                    $("#event-title").text(data[0]['name'] + " | " + formatDate(new Date(data[0][
                        'start_time'
                    ])));
                    $("#event-text").html(data[0]['description']).text();
                    if (data[0]['routes'].length > 0) {
                        let routeText =
                            `<h6 class="text-muted mb-3 p-1"><strong>Suggested Route(s):</strong></h6>`;
                        data[0]['routes'].forEach((value, i) => {
                            routeText +=
                                `<p class="text-muted px-1 ${i > 0 ? 'border-top pt-3' : ''}"> <strong>${value['departure']} - ${value['arrival']}</strong>: ${value['route']}</p>`;
                        });
                        $("#event-routes").html(routeText).text();
                    } else {
                        $("#event-routes").html('No routes suggested.').text();
                    }

                    $("#event-routes").css('display', 'block');
                },
                error: () => {
                    $("#event-container").empty();
                    $("#event-container").append(`
                        <div class="alert alert-danger mt-3 text-center" role="alert">@lang('pilot.aerodromes.aerodrome.loading-event-failed-content.0')</div>
                    `);
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
    </script>

    <!-- Load online ATC Stations -->
    <script>
        $(document).ready(() => {
            let delOnline = false;
            let gndOnline = false;
            let twrOnline = false;
            let appOnline = false;
            let ctrOnline = false;

            $.ajax({
                url: '{{ route('api.loadActiveAtc', ['icao' => $aerodrome->icao]) }}',
                type: 'GET',
                success: (data) => {
                    $("#loading-text-atc").remove();

                    if (data.length == 0) {
                        $("#table-atc-container").empty();
                        $("#table-atc-container").append(
                            `<div class="alert alert-danger text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.online-atc-zero-stations') </div>`
                        );
                        return;
                    }

                    $.each(data, (key, value) => {
                        switch (value['callsign'].substr(value['callsign'].length - 3)) {
                            case 'DEL':
                                delOnline = true;
                                break;
                            case 'GND':
                                gndOnline = true;
                                break;
                            case 'TWR':
                                twrOnline = true;
                                break;
                            case 'APP':
                                appOnline = true;
                                break;
                            case 'CTR':
                                ctrOnline = true;
                                break;
                        }

                        $("#table-active-atc").append(
                            `<tr>
                                <td class="text-center">${value['callsign']}</td>
                                <td class="text-center">${value['frequency']}</td>
                            </tr>`
                        )
                    })


                    delOnline ? $("#del_indicator").addClass('bg-soft-success').removeClass(
                        'bg-soft-danger') : null;
                    gndOnline ? $("#gnd_indicator").addClass('bg-soft-success').removeClass(
                        'bg-soft-danger') : null;
                    twrOnline ? $("#twr_indicator").addClass('bg-soft-success').removeClass(
                        'bg-soft-danger') : null;
                    appOnline ? $("#app_indicator").addClass('bg-soft-success').removeClass(
                        'bg-soft-danger') : null;
                    ctrOnline ? $("#ctr_indicator").addClass('bg-soft-success').removeClass(
                        'bg-soft-danger') : null;
                },
                error: () => {
                    $("#table-atc-container").empty();
                    $("#table-atc-container").append(
                        `<div class="alert alert-danger text-center" role="alert"> @lang('pilot.aerodromes.aerodrome.online-atc-error-loading') </div>`
                    );
                },
            })
        });
    </script>
@endpush
