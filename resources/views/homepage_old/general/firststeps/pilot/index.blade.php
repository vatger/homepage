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
                            <a href="{{ route('getting-started') }}" class="btn btn-primary"></a>
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
    <!-- Hero End -->

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

            @auth @if (Auth::user()->settings->dark_mode)
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

    @push('custom-script')
        @vite(['resources/js/tiny-slider.js'])
        @vite(['resources/js/custom/general/landing.js'])
    @endpush
