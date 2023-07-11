{{-- prettier-ignore-start --}}
@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url(" {{ asset('images/pilots/aerodromes_1.png') }} ")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 85%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">Network Livemap</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        Network Livemap
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

    <section class="section">
        <div class="container-fluid m-0">
            <div class="row">
                <!-- BLog Start -->
                <div class="col-12 mb-4">
                    <div class="w-100" id="map-container">
                        <div id="map" class="w-100" style="height: 900px">
                            <div id="mapSidebar" class="sidebar flex-center left collapsed">
                                <div class="sidebar-content rounded-rect flex-center">
                                    <div id="mapSidebarContent" class="w-100"></div>
                                    <div id="mapSidebarToggler" class="sidebar-toggle rounded-rect left" onclick="toggleSidebar()">
                                        &rarr;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet">
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
            min-height: 350px;
            width: 100%;
            
			@if (\Auth::check() && \Auth::user()->settings->dark_mode)background: linear-gradient(to right, transparent 0%, rgb(64 64 64 / 39%) 50%, transparent 100%);
		    @else background: linear-gradient(to right, transparent 0%, rgb(229, 229, 229) 50%, transparent 100%);
			@endif animation: 1.5s ease-in-out 0s infinite normal none running; animation-name: load;
        }

        .marker-plane {
            @if (Auth::check() && Auth::user()->settings->dark_mode)
            background-image: url('{{ asset('images/plane-dark.svg') }}');
            @else
            background-image: url('{{ asset('images/plane.svg') }}');
            @endif
            background-size: cover;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            cursor: pointer;
        }

        .flex-center {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .flex-center.left {
            left: 0px;
        }

        .flex-center.right {
            right: 0px;
        }

        .sidebar-content {
            position: absolute;
            width: 95%;
            height: 95%;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 32px;
            color: gray;
        }

        .sidebar-toggle {
            position: absolute;
            width: 1.3em;
            height: 1.3em;
            overflow: visible;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-toggle.left {
            right: -1.5em;
        }

        .sidebar-toggle.right {
            left: -1.5em;
        }

        .sidebar-toggle:hover {
            color: #0aa1cf;
            cursor: pointer;
        }

        .sidebar {
            transition: transform 1s;
            z-index: 1;
            width: 300px;
            height: 100%;
        }

        /*
                The sidebar styling has them "expanded" by default, we use CSS transforms to push them offscreen
                The toggleSidebar() function removes this class from the element in order to expand it.
                */
        .left.collapsed {
            transform: translateX(-295px);
        }

        .right.collapsed {
            transform: translateX(295px);
        }
    </style>
@endpush

@push('custom-script')
    <script src='https://unpkg.com/@turf/turf@6/turf.min.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>

    <!-- Load Map Data -->
    <script>
        let styleUrl = "";

        @if (Auth::check() && Auth::user()->settings->dark_mode)
            styleUrl = "mapbox://styles/nikki2048/ckyg12wrq5h6b15pcb4b4dev1";
        @else
            styleUrl = "mapbox://styles/nikki2048/ckyg6998m2ec515o86wkmkjnn";
        @endif

        mapboxgl.accessToken =
            'pk.eyJ1Ijoibmlra2kyMDQ4IiwiYSI6ImNrOXpibmR5bTA1MTIzZnJ0aXh1cG4yNjYifQ.b-1gEcULFsxkvP2s9BCXQg';
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: styleUrl, // style URL
            center: [10.492061, 51.436974], // starting position [lng, lat]
            zoom: 5, // starting zoom
            renderWorldCopies: false,
        });

        map.addControl(new mapboxgl.FullscreenControl());

        map.on('zoom', () => {
            // updatePilotVisibility(); // Is handled via moveend

            let width = 24 * ((map.getZoom()) / 10);
            let height = 24 * ((map.getZoom()) / 10);
            $(".marker-plane").css('width', width);
            $(".marker-plane").css('height', height);
        });

        map.on('moveend', () => {
            updatePilotVisibility();
        })

        let sidebarCollapsed = true;

        function setSidebarPilotContent(pilot) {
            const sidebarContentElement = document.getElementById('mapSidebarContent');
            // console.log(pilot);
            sidebarContent = `<div class="card w-100 text-primary">
                    <div class="card-header text-center">
                        <h5>${pilot.callsign}</h5>
                    </div>
                    <div class="card-body">`
            if(pilot.flight_plan !== null) {
                        sidebarContent += `<div class="table-responsive">
                            <table class="table fs-6">
                                <tbody>
                                    <tr>
                                        <th>Departure</th>
                                        <td>${pilot.flight_plan.departure}</td>
                                    </tr>
                                    <tr>
                                        <th>Destination</th>
                                        <td>${pilot.flight_plan.arrival}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h6 class="fs-5">Route <span class="fs-6">${pilot.flight_plan.revision_id}</span></h6>
                        <p class="fs-6">${pilot.flight_plan.route}</p>
                        <h6 class="fs-5">Latitude</h6>
                        <p class="fs-6">${pilot.latitude}</p>
                        <h6 class="fs-5">Longitude</h6>
                        <p class="fs-6">${pilot.longitude}</p>
                        <h6 class="fs-5">Groundspeed</h6>
                        <p class="fs-6">${pilot.groundspeed}</p>
                        <h6 class="fs-5">Heading</h6>
                        <p class="fs-6">${pilot.heading}</p>
                        <h6 class="fs-5">Altitude</h6>
                        <p class="fs-6">${pilot.altitude}</p>
                        <h6 class="fs-5">QNH (mb/hg)</h6>
                        <p class="fs-6">${pilot.qnh_mb} / ${pilot.qnh_i_hg}</p>
                        <h6 class="fs-5">Squawk</h6>
                        <p class="fs-6">${pilot.transponder}</p>`
            } else {
                sidebarContent += `<span class="bg-soft-warning fs-6">No flight plan filed!</span>`
            }
            sidebarContent += `</div>
                </div>`;

            sidebarContentElement.innerHTML = sidebarContent;

            if (sidebarCollapsed) {
                toggleSidebar();
            }
        }

        function setSidebarControllerContent(callsign) {
            axios.get('/livemap/atc/'+callsign)
                .then(res => {
                    let controllerDetails = res.data;
                    const sidebarContentElement = document.getElementById('mapSidebarContent');
                    sidebarContent = `<div class="card w-100 text-primary">
                            <div class="card-header text-center">
                                <h5>${callsign}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list list-unstyled">
                                    <li class="list-item fs-6">${controllerDetails.frequency}</li>
                                </ul>
                                <p class="fs-6">${controllerDetails.text_atis}</p>
                            </div>
                        </div>`;
                    sidebarContentElement.innerHTML = sidebarContent;
                    if(sidebarCollapsed) {
                        toggleSidebar();
                    }
                });
        }

        function toggleSidebar() {
            const sidebarElement = document.getElementById('mapSidebar');
            sidebarCollapsed = sidebarElement.classList.toggle('collapsed');

            if(sidebarCollapsed) {
                document.getElementById('mapSidebarToggler').innerHTML = `&rarr;`;
            } else {
                document.getElementById('mapSidebarToggler').innerHTML = `&larr;`;
            }

            map.easeTo({
                padding: sidebarCollapsed ? 0 : 300,
                duration: 1000
            });
        }

        async function getConnectedAtc() {
            let connectedAtc;
            await axios.get('{{ route('pilots.livemap.atc') }}')
                .then(res => {
                    connectedAtc = res.data;
                });
            return connectedAtc;
        }

        async function getConnectedPilots() {
            let connectedPilots;
            await axios.get('{{ route('pilots.livemap.pilots') }}')
                .then(res => {
                    connectedPilots = res.data;
                });
            return connectedPilots;
        }

        function buildSector(callsign, coordinates, i = 0) {
            map.addSource(callsign + i, {
                'type': 'geojson',
                'data': {
                    'type': 'Feature',
                    'properties': {},
                    'geometry': {
                        'type': 'Polygon',
                        'coordinates': coordinates
                    }
                }
            });
            map.addLayer({
                'id': callsign + i,
                'type': 'fill',
                'source': callsign + i,
                'layout': {
                    "fill-sort-key": i,
                },
                'paint': {
                    'fill-color': '#555',
                    'fill-opacity': 0.3,

                }
            });
            map.on('click', callsign+i, (e) => {
                setSidebarControllerContent(callsign);
            });
        }

        async function getSector(callsign) {
            await axios.get('/livemap/sector/' + callsign)
                .then(res => {
                    if (res && res.data.multiple) {
                        // We have subsectors
                        let i = 0;
                        res.data.points.forEach(subsector => {
                            let coordinates = [];
                            subsector.forEach(coords => {
                                var latLng = [parseFloat(coords[1]), parseFloat(coords[0])];
                                if (!coordinates.includes(latLng)) {
                                    coordinates.push(turf.point(latLng));
                                }
                            });
                            var points = turf.featureCollection(
                                coordinates
                            );

                            var hull = turf.convex(points);
                            turf.featureEach(hull, function(currentFeature, index) {
                                buildSector(callsign, currentFeature.geometry.coordinates, i);
                            });
                            i++;
                        });

                    } else {
                        // Hey just a single poly :)
                        if (res.data.points != undefined && res.data.points.length > 0) {
                            let sectorCoordinates = [];
                            res.data.points.forEach(coords => {
                                sectorCoordinates.push([parseFloat(coords[1]), parseFloat(coords[0])]);
                            });
                            buildSector(callsign, [sectorCoordinates]);
                        }
                    }

                });
        }

        let currentMarkers = [];

        function displayPilot(pilot) {
            if (currentMarkers[pilot.callsign] !== undefined && currentMarkers[pilot.callsign] !== null) {
                // Update current marker
                var marker = currentMarkers[pilot.callsign];
                marker.setLngLat([pilot.longitude, pilot.latitude]).setRotation(pilot.heading);
                if (map.getBounds().contains(marker.getLngLat())) {
                    marker.addTo(map);
                } else {
                    marker.remove();
                }

                currentMarkers[pilot.callsign] = marker;
            } else {
                const planeIconElement = document.createElement('div');
                planeIconElement.className = 'marker-plane';
                planeIconElement.id = 'plane' + pilot.callsign;

                var marker = new mapboxgl.Marker(planeIconElement)
                    .setLngLat([pilot.longitude, pilot.latitude])
                    .setRotation(pilot.heading)

                if (map.getBounds().contains(marker.getLngLat())) {
                    marker.addTo(map);
                }

                planeIconElement.addEventListener('click', () => {
                    setSidebarPilotContent(pilot);
                })

                currentMarkers[pilot.callsign] = marker;
            }
        }

        function updatePilotVisibility() {
            for (const callsign in currentMarkers) {
                const marker = currentMarkers[callsign];
                marker.remove();
                if (map.getBounds().contains(marker.getLngLat())) {
                    marker.addTo(map);
                }
            }
        }

        function loadAtcData() {
            $.when(getConnectedAtc()).done(function(connectedAtc) {
                for (i = 0; i < connectedAtc.length; i++) {
                    let sectorType = connectedAtc[i].callsign.split("_").pop();
                    switch (sectorType) {
                        case "DEL":
                        case "GND":
                            $.when(getSector(connectedAtc[i].callsign)).done();
                            break;
                        case "TWR":
                            $.when(getSector(connectedAtc[i].callsign)).done();
                            break;
                        case "DEP":
                        case "APP":
                            $.when(getSector(connectedAtc[i].callsign)).done();
                            break;
                        case "CTR":
                        case "FSS":
                            $.when(getSector(connectedAtc[i].callsign)).done();
                            break;
                        default:
                            break;
                    }
                }
            });
        }

        function loadPilotData() {
            $.when(getConnectedPilots()).done(function(connectedPilots) {
                for (const cp of connectedPilots) {
                    displayPilot(cp);
                }
            });
        }

        $(document).ready(() => {
            loadAtcData();
            loadPilotData();
            setInterval(() => {
                loadAtcData();
                loadPilotData();
            }, 120 * 1000);
        });
    </script>
@endpush
{{-- prettier-ignore-end --}}
