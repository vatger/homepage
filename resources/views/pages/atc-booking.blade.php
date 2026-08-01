@extends('layouts.master')

@section('content')
    <x-layouts.content :header="__('booking.atc.title')"
        :links="[route('landing') => config('app.name'), __('navigation.lotsen.titel'), route('controllers.booking') => __('booking.atc.title')]"
        :backgroundurl="iasset('images/bookings/booking_1.png')" />

    <section class="section">
        <div class="mx-auto grid w-full max-w-[1750px] gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div class="lg:col-span-2">
                <livewire:atc.list-atc-booking-tab />
            </div>
            <aside>
                <livewire:atc.book-position-tab />
            </aside>
        </div>
    </section>
@endsection
