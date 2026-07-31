@extends('layouts.master')

@section('content')
    <!-- Hero Start -->
    <section class="landing-hero bg-half-260 d-table w-100" id="hero-section"
             style="background: url('{{ iasset('images/hero-banners/hero_' . rand(1, 9) . '.png') }}') center center; background-size: cover">
        <div class="bg-overlay landing-hero-overlay"></div>
        <div class="container">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-xl-7 col-lg-8 col-md-11">
                    <div class="title-heading mt-4 text-center text-lg-start">
                        <span class="landing-kicker">@lang('landing.welcome.badge-text')</span>
                        <h1 class="heading landing-hero-title mt-4 mb-3 text-white">VATSIM Germany</h1>
                        <p class="landing-hero-copy text-white-50" id="typewriter">@lang('pages.landing.hero-fallback')</p>
                        <div class="mt-4 d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                            <a href="{{ route('redirect.knowledgebase.start') }}" class="btn btn-lg rounded-pill px-4 text-white">@lang('landing.join-now-button-content')</a>
                            <a href="#welcome" class="btn btn-outline-white btn-lg rounded-pill px-4 text-white">@lang('landing.read-more-button-content')</a>
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
    <div class="position-relative landing-shape">
        <div class="shape overflow-hidden">
            <svg viewBox="0 0 2880 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M720 125L2160 0H2880V250H0V125H720Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- Hero End -->

    <x-landing.welcome></x-landing.welcome>

    <x-landing.events></x-landing.events>

    <x-landing.partners :partners="\App\Models\Partner::all()"></x-landing.partners>
@endsection

@push('scripts')
    @vite(['resources/ts/special/landing-typewriter.ts'])
@endpush
