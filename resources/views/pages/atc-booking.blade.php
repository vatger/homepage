@extends('layouts.master')

@section('content')
    <div>
        @component('components.layouts.content',[
            'header' => __('booking.atc.title'),
            'links' => [
                route('landing') => config('app.name'),
            'Controllers',
            route('controllers.booking') => __('booking.atc.title')
            ],
            'backgroundurl' => asset('images/bookings/booking_' . rand(1, 1) . '.png')
        ])
        @endcomponent

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
