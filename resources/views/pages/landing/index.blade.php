@extends('layouts.master')

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

@endsection
