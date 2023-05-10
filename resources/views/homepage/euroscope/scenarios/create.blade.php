@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{ asset('images/hero-banners/hero_1.png') }}")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">EuroScope SimSessions</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">VATGER</a></li>
                        <li class="breadcrumb-item active" aria-current="page">EuroScope SimSessions</li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Hero End -->

    <!-- Shape Start -->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!--Shape End-->

    <!-- Blog STart -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow border-0 rounded">
                        <div class="card-body">
                            <form action="{{ route('euroscope.scenarios.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Session Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="icao">Aerodrome ICAO <span class="text-muted">Multiple ICAO codes supported by separating them
                                            with a comma (,).</span></label>
                                    <input type="text" class="form-control @error('icao') is-invalid @enderror" id="icao" name="icao"
                                        value="{{ old('icao') }}">
                                    @error('icao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="range">Range <span class="text-muted">Maximum is 500. The maximum distance allowed for flights to
                                            be considered. Does include departures. For APP/TWR/GND/DEL: keep this reasonable.</span></label>
                                    <input type="range" class="form-range @error('range') is-invalid @enderror" id="range" name="range"
                                        min="15" max="500" value="{{ old('range') ?? 350 }}" oninput="this.form.rangeInput.value=this.value">
                                    <input type="number" class="form-control @error('range') is-invalid @enderror" name="rangeInput" min="15"
                                        max="500" value="{{ old('range') ?? 350 }}" oninput="this.form.range.value=this.value">
                                    @error('range')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="maxFlights">Maximum Flights <span class="text-muted">Maximum is 1000, Minimum is 1. Number of total
                                            flights that CAN show up.</span></label>
                                    <input type="range" class="form-range @error('maxFlights') is-invalid @enderror" id="maxFlights" name="maxFlights"
                                        min="1" max="1000" value="{{ old('maxFlights') ?? 500 }}"
                                        oninput="this.form.maxFlightsInput.value=this.value">
                                    <input type="number" class="form-control @error('maxFlights') is-invalid @enderror" name="maxFlightsInput"
                                        min="1" max="1000" value="{{ old('maxFlights') ?? 500 }}"
                                        oninput="this.form.maxFlights.value=this.value">
                                    @error('maxFlights')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="depArrScale">Departure / Arrival Scale <span class="text-muted">Minimum is 0. Percentage of
                                            departures over arrivals. 0 = NO departures, 100 = ONLY departures.</span></label>
                                    <input type="range" class="form-range @error('depArrScale') is-invalid @enderror" id="depArrScale"
                                        name="depArrScale" min="0" max="100" value="{{ old('depArrScale') ?? 50 }}"
                                        oninput="this.form.depArrScaleInput.value=this.value">
                                    <input type="number" class="form-control @error('depArrScale') is-invalid @enderror" id="depArrScaleInput"
                                        name="depArrScaleInput" min="0" max="100" value="{{ old('depArrScale') ?? 50 }}"
                                        oninput="this.form.depArrScale.value=this.value">
                                    @error('depArrScale')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="depAltLimit">Departure Altitude Limit <span class="text-muted">Minimum is 0. Maximum is FL470.
                                            Only display departures at or below this threshold</span></label>
                                    <input type="range" class="form-range @error('depAltLimit') is-invalid @enderror" id="depAltLimit"
                                        name="depAltLimit" min="0" max="47000" value="{{ old('depAltLimit') ?? 15000 }}"
                                        oninput="this.form.depAltLimitInput.value=this.value">
                                    <input type="number" class="form-control @error('depAltLimit') is-invalid @enderror" id="depAltLimitInput"
                                        name="depAltLimitInput" min="0" max="47000" value="{{ old('depAltLimit') ?? 15000 }}"
                                        oninput="this.form.depAltLimit.value=this.value">
                                    @error('depAltLimit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="minSquawk">Minimum Squawk <span class="text-muted">Minimum is 0001. Must be smaller than Maximum
                                            Squawk.</span></label>
                                    <input type="range" class="form-range @error('minSquawk') is-invalid @enderror" id="minSquawk" name="minSquawk"
                                        min="0001" max="7777" value="{{ old('minSquawk') }}"
                                        oninput="this.form.minSquawkInput.value=this.value">
                                    <input type="number" class="form-control @error('minSquawk') is-invalid @enderror" id="minSquawkInput"
                                        name="minSquawkInput" min="0001" max="7777" value="{{ old('minSquawk') }}"
                                        oninput="this.form.minSquawk.value=this.value">
                                    @error('minSquawk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="maxSquawk">Maximum Squawk <span class="text-muted">Maximum is 7777. Must be greater than Minimum
                                            Squawk.</span></label>
                                    <input type="range" class="form-range @error('maxSquawk') is-invalid @enderror" id="maxSquawk" name="maxSquawk"
                                        min="0001" max="7777" value="{{ old('maxSquawk') }}"
                                        oninput="this.form.maxSquawkInput.value=this.value">
                                    <input type="number" class="form-control @error('maxSquawk') is-invalid @enderror" id="maxSquawkInput"
                                        name="maxSquawkInput" min="0001" max="7777" value="{{ old('maxSquawk') }}"
                                        oninput="this.form.maxSquawk.value=this.value">
                                    @error('maxSquawk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="initialPseudo">Initial Pseudopilot <span class="text-muted">The station that is the session
                                            mentor.</span></label>
                                    <input type="text" class="form-control @error('initialPseudo') is-invalid @enderror" id="initialPseudo"
                                        name="initialPseudo" value="{{ old('initialPseudo') }}">
                                    @error('initialPseudo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="holdings">Holdings <span class="text-muted">One holding per line. Line format: FIX:INBD
                                            CRS:DIRECTION. Direction: -1 left, 1 right</span></label>
                                    <textarea class="form-control @error('holdings') is-invalid @enderror" id="holdings" name="holdings">{{ old('holdings') }}</textarea>
                                    @error('holdings')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <label for="runways">Runways <span class="text-muted">One runway per line, maximum of 4 lines. Line format:
                                            ILS&lt;runway name&gt;:&lt;threshold latitude&gt;:&lt;threshold longitude&gt;:&lt;runway
                                            heading&gt;</span></label>
                                    <textarea class="form-control @error('runways') is-invalid @enderror" id="runways" name="runways">{{ old('runways') }}</textarea>
                                    @error('runways')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-block btn-primary w-100">Create Scenario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!-- Blog End -->
@endsection
