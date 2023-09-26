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
                        <p class="para-desc text-white-50" id="typewriter">Controlling The Virtual German Airspace With Passion!</p>
                        <div class="mt-4">
                            <a href="{{ route('redirect.knowledgebase.start') }}" class="btn btn-primary">@lang('landing.join-now-button-content')</a>
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

    <x-landing.partners :partners="\App\Models\Partner::all()"></x-landing.partners>

    <x-landing.welcome></x-landing.welcome>

    <x-landing.events></x-landing.events>
@endsection

@push('scripts')
    @vite(['resources/ts/special/landing-typewriter.ts'])
@endpush
