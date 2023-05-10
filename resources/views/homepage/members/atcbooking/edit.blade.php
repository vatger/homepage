@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
        style="background: url('{{ asset('images/bookings/booking_' . rand(1, 1) . '.png') }}') center center; background-size: cover">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">@lang('booking.atc.edit.title')</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('controllers.booking.index') }}">@lang('booking.atc.title')</a></li>
                        <li class="breadcrumb-item active">@lang('booking.atc.edit.breadcrumb')</li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
    <!--end section-->
    </div>
    <!-- Hero End -->

    <section class="section pt-md-5 pt-5">
        <div class="container">
            <div class="col-lg-12">
                <div class="card blog blog-detail border-0 shadow rounded">
                    <div class="card-body content">
                        <form id="edit-booking-form mt-5" action="{{ route('controllers.booking.update', $booking) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row" id="edit-container">
                                <div class="col-12 mb-3">
                                    <label class="form-label">@lang('booking.atc.edit.date-text')</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book fea icon-sm icons">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <input name="date" id="date-select" type="text" class="form-control ps-5"
                                            value="{{ $booking->starts_at->format('d.m.Y') }}">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">@lang('booking.atc.edit.start-time-text')</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book fea icon-sm icons">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <input name="start_at" id="start-time-select" type="text" class="form-control ps-5"
                                            value="{{ $booking->starts_at->format('H:i') }}">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">@lang('booking.atc.edit.end-time-text')</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book fea icon-sm icons">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <input name="end_at" id="end-time-select" type="text" class="form-control ps-5"
                                            value="{{ $booking->ends_at->format('H:i') }}">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check p-0">
                                        <label class="form-check-label" for="position-selector">@lang('booking.atc.edit.station-text')</label>
                                        <select class="form-select form-control" aria-label="Station selector" id="position-selector" name="position">
                                            @foreach ($positions as $s)
                                                <option @if ($booking->station_id == $s->id) selected @endif value="{{ $s->ident }}">{{ $s->ident }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label" for="voice-selector">@lang('booking.atc.edit.voice-text')</label>
                                        <input class="form-check-input" type="checkbox"
                                            @if ($booking->voice) checked value="true" @else value="false" @endif id="voice-selector"
                                            name="voice">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label" for="event-selector">@lang('booking.atc.edit.event-text')</label>
                                        <input class="form-check-input" type="checkbox"
                                            @if ($booking->event) checked value="true" @else value="false" @endif id="event-selector"
                                            name="event">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label" for="training-selector">@lang('booking.atc.edit.training-text')</label>
                                        <input class="form-check-input" type="checkbox"
                                            @if ($booking->training) checked value="true" @else value="false" @endif id="training-selector"
                                            name="training">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="submit" id="save-booking-button" class="btn btn-soft-primary" value="@lang('booking.atc.edit.save-button-text')">
                                </div>
                                <!--end col-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .daterangepicker {
            color: black !important;
        }
    </style>
@endsection

@push('custom-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(function() {
            $('#date-select').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                locale: {
                    format: 'DD.MM.YYYY',
                },
                timePicker: false,
            }, function(start, end, label) {

            });

            $("#start-time-select").daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                timePickerSeconds: false,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', (ev, picker) => {
                picker.container.find(".calendar-table").hide();
            });

            $("#end-time-select").daterangepicker({
                timePicker: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                timePickerIncrement: 5,
                timePickerSeconds: false,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', (ev, picker) => {
                picker.container.find(".calendar-table").hide();
            });
        });
    </script>
@endpush
