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

    @include('homepage.general.landing.partners')

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
                    <button type="button" class="btn btn-pills btn-soft-primary" id="show-events-btn" disabled> Show More </button>
                </div>
            </div>
            <!-- End Features -->
        </div>
    </section>
    <!--end section-->
    <!-- section End -->

    @include('homepage.general.landing.atcbookings')

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
            @auth @if (\Auth::user()->settings->dark_mode)background: linear-gradient(to right, transparent 0%, rgb(64 64 64 / 39%) 50%, transparent 100%);
        @else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
            @endif@else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
        @endauth animation: 1.5s ease-in-out 0s infinite normal none running;
        animation-name: load;
    }
</style>
@endsection

@push('custom-script')
<script src="{{ asset('/js/tiny-slider.js') }}"></script>
<script src="{{ asset('/js/custom/general/landing.js') }}"></script>
@endpush
