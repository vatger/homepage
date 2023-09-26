@extends('homepage.pilots.aerodromes.partial.hero')

@section('hero-img-src')
    @if (file_exists(public_path('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg')))
        {{ asset('images/aerodromes/' . strtolower($aerodrome->icao) . '.jpg') }}
    @else
        {{ asset('images/pilots/aerodromes_' . rand(1, 2) . '.png') }}
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
    @lang('pilot.aerodromes.aerodrome.breadcrumb.0')
    <li class='breadcrumb-item'><a href="{{ route('pilots.aerodromes.view', $aerodrome->icao) }}">{{ $aerodrome->icao }}</a>
    </li>
    <li class='breadcrumb-item active'>Charts</li>
@endsection

@section('aerodrome-content')
    <section class="section">
        <div class="container">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-lg-8 col-md-6 mb-4">
                    <div class="card blog blog-detail border-0 shadow rounded">
                        <div class="card-body content">
                            <h4>Aerodrome Charts</h4>
                            <div class="w-100 table-responsive">
                                <table class="table table-hover table-center">
                                    <thead>
                                    <tr>
                                        <th style="width: 20%;" scope="col" class="border-bottom text-center">Type</th>
                                        <th style="width: 20%;" scope="col" class="border-bottom text-center">Name</th>
                                        <th style="width: 20%;" scope="col" class="border-bottom text-center">AIRAC</th>
                                        <th style="width: 20%;" scope="col" class="border-bottom text-center">Last Modified</th>
                                        <th style="width: 20%;" scope="col" class="border-bottom text-center">Link</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($charts as $chart)
                                        @if (is_array($chart))
                                            <tr class="text-center">
                                                <td>{{ Str::upper($chart['type']) }}</td>
                                                <td>{{ $chart['name'] }}</td>
                                                <td>{{ $chart['airac'] }}</td>
                                                <td>
                                                    {{ isset($chart['revised_at']) ? $chart['revised_at'] : \Carbon\Carbon::parse($chart['updated_at'])->format('d.m.Y') }}
                                                </td>
                                                <td>
                                                    @if (isset($chart['link']))
                                                        {{-- THESE ARE AIP LINKS --}}
                                                        <a href="{{ $chart['link'] }}" class="nav-link" target="_blank">
                                                            <button type="button"
                                                                    class="btn btn-sm btn-soft-primary">Download
                                                            </button>
                                                        </a>
                                                    @else
                                                        {{-- LOCAL (NAV SVN) CHARTS --}}
                                                        @if ($chart['fri'] == 'vfr' && ($token = \App\Libraries\ChartAuthorization::grantAccessToken($chart['id'])))
                                                            <a href="{{ $chart['href'] }}?token={{ $token }}" class="nav-link"
                                                               target="_blank">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-soft-primary">Download
                                                                </button>
                                                            </a>
                                                        @elseif ($chart['fri'] == 'ifr')
                                                            <a href="{{ $chart['href'] }}" class="nav-link" target="_blank">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-soft-primary">Download
                                                                </button>
                                                            </a>
                                                        @else
                                                            <span class="text-danger">Unauthorized</span>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{{ Str::upper($chart->type) }}</td>
                                                <td>{{ $chart->name }}</td>
                                                <td>{{ $chart->airac }}</td>
                                                <td>{{ isset($chart->revised_at) ? $chart->revised_at : $chart->updated_at->format('Y/m/d') }}</td>
                                                <td>
                                                    @if (isset($chart->link))
                                                        {{-- THESE ARE AIP LINKS --}}
                                                        <a href="{{ $chart->link }}" class="nav-link" target="_blank">View</a>
                                                    @else
                                                        {{-- LOCAL (NAV SVN) CHARTS --}}
                                                        @if ($chart->fri == 'vfr' && ($token = \App\Libraries\ChartAuthorization::grantAccessToken($chart)))
                                                            <a href="{{ $chart->href }}?token={{ $token }}" class="nav-link"
                                                               target="_blank">Download</a>
                                                        @elseif ($chart['fri'] == 'ifr')
                                                            <a href="{{ $chart->href }}" class="nav-link" target="_blank">Download</a>
                                                        @else
                                                            Unauthorized
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
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
                                    @if ($aerodrome->useChartfox)
                                        <button onclick="showChartfoxModal()" type="button" class="btn btn-soft-primary"
                                                style="width: 90%; margin-left: 5%">ChartFox
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                 height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link fea icon-sm"
                                                 style="margin-left: 3px; margin-top:-2px; height: 14px; width: 14px">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('pilots.aerodromes.view', ['icao' => $aerodrome->icao]) }}">
                                        <button type="button" class="btn btn-soft-primary mt-3" style="width: 90%; margin-left: 5%">@lang('general.navigation.back')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-external-link fea icon-sm" style="margin-left: 10px; margin-top:-4px">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </button>
                                    </a>
                                    <a href="https://wiki.vatsim-germany.org" target="_blank">
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
@endsection

@push('custom-script')
    <script src="{{ asset('js/custom/modal.js') }}"></script>

    <!-- Chartfox Modal -->
    <script>
      const chartfoxBaseUrl = "https://chartfox.org/";
      const chartfoxModal = new Modal(modalTypes.warning, '@lang('pilot.aerodromes.aerodrome.charts.chartfox.warning-title')');
      chartfoxModal.bodyContent = `<p class="text-muted">@lang('pilot.aerodromes.aerodrome.charts.chartfox.warning-text')</p>`;
      chartfoxModal.addButton(() => {
        window.open(chartfoxBaseUrl + "{{ $aerodrome->icao }}", "_blank").focus();
        chartfoxModal.hide();
      }, "btn-soft-primary", "@lang('pilot.aerodromes.aerodrome.charts.chartfox.button-content')");

      chartfoxModal.create();

      function showChartfoxModal() {
        chartfoxModal.show();
      }
    </script>

    <!-- Load Metar -->
    <script>
      $(document).ready(() => {
        $.ajax({
          url: '{{ route('api.loadMetar') }}',
          type: "GET",
          data: {
            icao: '{{ $aerodrome->icao }}'
          },
          success: (data) => {
            if (data == "") {
              $("#metar-container").empty();
              $("#metar-container").append(
                `<div class="alert alert-danger" role="alert"> @lang('pilot.aerodromes.aerodrome.error-metar-load-text') </div>`
              );
              return;
            }

            $("#metar-container").text(data);
          },
          error: (xhr, http, data) => {
            $("#metar-container").empty();
            $("#metar-container").append(
              `<div class="alert alert-danger" role="alert"> @lang('pilot.aerodromes.aerodrome.error-metar-load-text') </div>`
            );
          }
        });
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
          type: "GET",
          success: (data) => {
            $("#loading-text-atc").remove();

            if (data.length == 0) {
              $("#table-atc-container").empty();
              $("#table-atc-container").append(
                `<div class="alert alert-danger" role="alert"> @lang('pilot.aerodromes.aerodrome.online-atc-zero-stations') </div>`
              );
              return;
            }

            $.each(data, (key, value) => {
              switch (value["callsign"].substr(value["callsign"].length - 3)) {
                case "DEL":
                  delOnline = true;
                  break;
                case "GND":
                  gndOnline = true;
                  break;
                case "TWR":
                  twrOnline = true;
                  break;
                case "APP":
                  appOnline = true;
                  break;
                case "CTR":
                  ctrOnline = true;
                  break;
              }

              $("#table-active-atc").append(
                `<tr>
                                <td class="text-center">${value["callsign"]}</td>
                                <td class="text-center">${value["frequency"]}</td>
                            </tr>`
              );
            });

            delOnline ? $("#del_indicator").addClass("bg-soft-success").removeClass(
              "bg-soft-danger") : null;
            gndOnline ? $("#gnd_indicator").addClass("bg-soft-success").removeClass(
              "bg-soft-danger") : null;
            twrOnline ? $("#twr_indicator").addClass("bg-soft-success").removeClass(
              "bg-soft-danger") : null;
            appOnline ? $("#app_indicator").addClass("bg-soft-success").removeClass(
              "bg-soft-danger") : null;
            ctrOnline ? $("#ctr_indicator").addClass("bg-soft-success").removeClass(
              "bg-soft-danger") : null;
          },
          error: () => {
            $("#table-atc-container").empty();
            $("#table-atc-container").append(
              `<div class="alert alert-danger" role="alert"> @lang('pilot.aerodromes.aerodrome.online-atc-error-loading') </div>`
            );
          }
        });
      });
    </script>
@endpush
