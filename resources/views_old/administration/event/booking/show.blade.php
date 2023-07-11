@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Event Booking</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize">Events</li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Booking</li>
                    </ul>
                </nav>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title">{{ $event->name }}</h5>
                            <p class="card-subtitle">{{ \Carbon\Carbon::parse($event->start_time)->format('d.m.Y H:i') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('d.m.Y H:i') }}</p>
                        </div>
                        <div class="card-body">
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    @foreach ($aerodromes as $aerodrome)
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="card-title">{{ $aerodrome->name }}</h5>
                            </div>
                            <div class="card-body">
                                <p>Buche Stationen an diesem Airport für den Eventzeitraum.</p>
                                <form action="{{ route('administration.event.booking.update', ['eventId' => $event->id]) }}" method="post">
                                    @csrf
                                    <div class="row">
                                        @foreach ($aerodrome->stations as $station)
                                            <div class="col-12 mb-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="station-selector{{ $station->id }}">{{ $station->name }}</label>
                                                    <input class="form-check-input" type="checkbox" value="true"
                                                        id="station-selector{{ $station->id }}" name="station{{ $station->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-12">
                                            <input type="submit" id="save-booking-button" class="btn btn-soft-primary" value="@lang('booking.atc.create.save-button-text')">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
