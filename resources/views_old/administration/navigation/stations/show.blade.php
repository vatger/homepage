@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $station->ident }} - {{ $station->name }}</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation.stations') }}">Stationsverwaltung</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $station->ident }}</li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col-12 mt-4">
                    <form id="stationEditForm" action="{{ route('administration.navigation.stations.update', ['station' => $station]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Identifier</label>
                                    <input type="text" name="ident" id="ident" class="form-control" value="{{ $station->ident }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Frequency</label>
                                    <input type="text" name="frequency" id="frequency" class="form-control" value="{{ $station->fixedFrequency }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $station->name }}">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="atis" value="0" />
                                        <input class="form-check-input" type="checkbox"
                                            @if ($station->atis) checked value="1" @else value="0" @endif id="atis-selector" name="atis">
                                        <label class="form-check-label" for="atis-selector">Is this station used to broadcast ATIS?</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="bookable" value="0" />
                                        <input class="form-check-input" type="checkbox"
                                            @if ($station->bookable) checked value="1" @else value="0" @endif id="bookable-selector"
                                            name="bookable">
                                        <label class="form-check-label" for="bookable-selector">Is this station bookable by controllers?</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check" style="min-width: 30%;">
                                        <label class="form-check-label" for="aerodrome-assign-selector">The aerodromes this station is assigned to.
                                            Multiple selections can be made.</label>
                                        <select multiple class="form-select form-control w-100 h-100" style="min-height: 350px;"
                                            aria-label="Aerodrome assignment selection" id="aerodrome-assign-selector" name="aerodromes[]">
                                            @foreach ($aerodromes as $aerodrome)
                                                <option value="{{ $aerodrome->id }}" @if ($station->aerodromes->contains($aerodrome)) selected @endif>
                                                    {{ $aerodrome->icao }} - {{ $aerodrome->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-soft-primary" id="patchStationButton" type="submit">Save Station</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-4 pt-4 border-top">
                    <form action="{{ route('administration.navigation.stations.delete', ['station' => $station]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger">Delete Station</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
