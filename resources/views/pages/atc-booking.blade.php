@extends('layouts.master')

@section('content')
    <div>
        <!-- Hero Start -->
        <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
                 style="background: url('{{ asset('images/bookings/booking_' . rand(1, 1) . '.png') }}') center center; background-size: cover">
            <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
            <div class="container">
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-12 text-center">
                        <div class="pages-heading">
                            <h2 style="color: white">@lang('booking.atc.title')</h2>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

                <div class="position-breadcrumb">
                    <nav aria-label="breadcrumb" class="d-inline-block">
                        <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                            <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                            <li class="breadcrumb-item active">@lang('booking.atc.title')</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </section>
        <!--end section-->
        <!-- Hero End -->

        <section class="section">
            <div class="container-md">
                <div class="row">
                    <!-- BLog Start -->
                    <div class="col-lg-7 col-md-6 mb-4">
                        <livewire:atcbooking.list-atc-booking-tab />
                    </div>
                    <!-- BLog End -->

                    <!-- START SIDEBAR -->
                    <livewire:atcbooking.book-position-tab />
                    <!-- END SIDEBAR -->
                </div>
                <!--end row-->
            </div>
            <!--end container-->
        </section>

    </div>
@endsection
