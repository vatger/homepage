@extends('homepage.pilots.aerodromes.partial.hero')

@section('title')
    @lang('pilot.aerodromes.title')
@endsection

@section('hero-img-src')
    {{ asset('images/pilots/aerodromes_' . rand(1, 2) . '.png') }}
@endsection

@section('breadcrumb')
    @lang('pilot.aerodromes.breadcrumb.0')
@endsection

@section('aerodrome-content')
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 mt-4 col-12">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0">
                        <div class="card-body p-0 content">
                            <div class="mb-3">
                                <label class="form-label">@lang('pilot.aerodromes.search-text')</label>
                                <div class="form-icon position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-book fea icon-sm icons">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    <input name="subject" id="aerodrome-search-input" class="form-control ps-5" type="text"
                                        placeholder="@lang('pilot.aerodromes.search-input-placeholder')">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="">
                                <ul class="container-filter list-inline mb-0 filter-options text-center">
                                    <li class="list-inline-item categories-name border text-dark rounded active fir-select" id="fir-all-select"
                                        data-fir="-1">All</li>
                                    @foreach (\App\Models\Regionalgroup\FlightInformationRegion::all() as $fir)
                                        <li class="list-inline-item border text-dark rounded fir-select" data-fir="{{ $fir->id }}">
                                            {{ $fir->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!--end col-->
                        <p class="text-muted mb-0" style="display: none" id="search-count-container">Die Suche ergab <span id="search-count"></span>
                            Treffer.</p>
                    </div>
                </div>
                <!--end col-->
            </div>

            <div class="row mb-1 text-center">
                <div class="col-lg-12 col-md-12 col-12 mt-4 pt-2 picture-item loading-container">
                    <span class="loader"></span>
                </div>
                <!--end col-->

                <div class="row" id="aerodrome-container" style="display: none; padding-right: 0 !important; left: 5px !important;">

                </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>

    <style>
        .loader {
            width: 40px;
            height: 40px;
            border: 5px solid #4068e1;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@push('custom-script')
    <script>
        $(document).ready(() => {
            let airportDict;
            let selector = $("#aerodrome-search-input");
            const redirUrl = '{{ route('pilots.aerodromes.view', ['icao' => ':icao']) }}'.toString().replace(':icao',
                '');
            let timer;

            const aerodromeContainer = $("#aerodrome-container");
            const loadingContainer = $(".loading-container");

            console.log(redirUrl);
            loadData();

            $(".fir-select").on('click', function() {
                let firId = $(this).data('fir');
                $(".active").removeClass('active');
                $(this).addClass('active');
                $("#aerodrome-search-input").val('');

                $("#search-count-container").css('display', 'none');
                lastText = '-1';

                if (firId === -1) {
                    loadData();
                } else {
                    searchData(null, firId);
                }
            });


            let lastText;
            selector.on('keyup', function(e) {
                if ($(this).val().length === 0) {
                    search();
                    return;
                }
                if (timer) {
                    clearTimeout(timer);
                }
                timer = setTimeout(search, 400);
            });

            /**
             * Calls the corresponding API call and includes the relevant data such as query_string
             */
            function search() {
                let text = selector.val();
                $(".active").removeClass('active');
                $("#fir-all-select").addClass('active');

                // Reset page to last downloaded state if no search_query present
                if (text.length === 0) {
                    $("#search-count-container").css('display', 'none');
                    lastText = '-1';
                    resetData();
                    return;
                }

                if (lastText !== text) {
                    searchData(text);
                    lastText = text;
                }
            }

            function resetData() {
                loadingContainer.css('display', 'block');
                aerodromeContainer.empty();
                aerodromeContainer.css('display', 'none');
                for (let i = 0; i < airportDict.length; i++) {
                    aerodromeContainer.append(`
                    <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item">
                        <a href="${redirUrl + airportDict[i]['icao']}">
                        <div class="card blog border-0 work-container work-primary work-classic shadow rounded-md overflow-hidden">
                            <div class="card-body">
                                <div class="content">
                                    <h5><span class="text-dark title">${airportDict[i]['icao']} ${airportDict[i]['iata'] ? '| ' + airportDict[i]['iata'] : ''}</span></h5>
                                    <p class="text-muted mb-0">${airportDict[i]['name']}</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div><!--end col-->
                `);
                }

                loadingContainer.css('display', 'none');
                aerodromeContainer.css('display', 'flex');
            }

            function searchData(search_string, search_fir = null) {
                $("#search-count-container").css('display', 'none');
                aerodromeContainer.css('display', 'none');
                loadingContainer.css('display', 'block');

                $.ajax({
                    url: '{{ route('api.pilots.aerodromes.search') }}',
                    method: 'GET',
                    data: {
                        'search_param': search_string,
                        'search_fir': search_fir,
                    },
                    success: (data) => {
                        $("#search-count").text(data.length);
                        $("#search-count-container").css('display', 'block');
                        aerodromeContainer.empty();

                        for (let i = 0; i < data.length; i++) {
                            aerodromeContainer.append(`
                         <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item">
                            <a href="${redirUrl + data[i]['icao']}">
                            <div class="card blog border-0 work-container work-primary work-classic shadow rounded-md overflow-hidden">
                                <div class="card-body">
                                    <div class="content">
                                        <h5><span class="text-dark title">${data[i]['icao']} ${data[i]['iata'] ? '| ' + data[i]['iata'] : ''}</span></h5>
                                        <p class="text-muted mb-0">${data[i]['name']}</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div><!--end col-->
                        `)
                        }

                        loadingContainer.css('display', 'none');
                        aerodromeContainer.css('display', 'flex');
                    }
                });
            }

            function loadData() {
                $.ajax({
                    url: '{{ route('api.pilots.aerodromes.getall') }}',
                    method: 'GET',
                    success: (data) => {
                        airportDict = data;
                        aerodromeContainer.empty();
                        for (let i = 0; i < data.length; i++) {
                            aerodromeContainer.append(`
                         <div class="col-lg-4 col-md-6 col-12 col-sm-12 mt-4 pt-2 picture-item">
                            <a href="${redirUrl + data[i]['icao']}">
                            <div class="card blog border-0 work-container work-primary work-classic shadow rounded-md overflow-hidden">
                                <div class="card-body">
                                    <div class="content">
                                        <h5><span class="text-dark title">${data[i]['icao']} ${data[i]['iata'] ? '| ' + data[i]['iata'] : ''}</span></h5>
                                        <p class="text-muted mb-0">${data[i]['name']}</p>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div><!--end col-->
                        `)
                        }

                        loadingContainer.css('display', 'none');
                        aerodromeContainer.css('display', 'flex');
                    },
                    error: (data) => {
                        console.error(data);
                    }

                });
            }
        });
    </script>
@endpush
