@extends('layouts.master')

@section('content')
    <div>
        @component('components.layouts.content',[
            'header' => __('booking.atc.title'),
            'links' => [
                route('landing') => config('app.name'),
            __('navigation.lotsen.titel'),
            route('controllers.booking') => __('booking.atc.title')
            ],
            'backgroundurl' => iasset('images/bookings/booking_' . rand(1, 1) . '.png')
        ])
        @endcomponent

        <section class="section">
            <div class="container-fluid" style="max-width: 1750px">
                <div class="row">
                    <!-- BLog Start -->
                    <div class="col-lg-8 col-md-6 mb-4">
                        <livewire:atc.list-atc-booking-tab />
                    </div>
                    <!-- BLog End -->

                    <!-- START SIDEBAR -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <livewire:atc.book-position-tab />
                    </div>
                    <!-- END SIDEBAR -->

                </div>
                <!--end row-->
            </div>
            <!--end container-->
        </section>

    </div>
@endsection
