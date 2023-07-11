@php use App\Http\Livewire\Eventrouteslist; @endphp
@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Route - {{ $eventRoute->name }} </h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize">Event</li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.prevent.routedev') }}">Routes</a></li>
                        <li class="breadcrumb-item text-capitalize " aria-current="page">{{ $eventRoute->name }}</li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">details</li>
                    </ul>
                </nav>
            </div>
            {{-- @livewire(Eventrouteslist::class) --}}
            <div class="row">
                <div class="col mt-4">
                    <div class="card shadow border-0">
                        <div class="row p-4 border-bottom">
                            <div class="col-lg-12 col-md-6 col-sm-12 mb-1">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <div class="d-flex align-items-center">
                                        <div class="icon text-center rounded-pill">
                                            <i class="mdi mdi-calendar fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Legs</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ count($eventRoute->legs) }}</p>
                                        </div>
                                        <div class="ms-4 icon text-center rounded-pill">
                                            <i class="mdi mdi-calendar fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Flightrule</h6>
                                            @if ($eventRoute->flight_rules = 'I')
                                                <p class="fs-5 text-dark fw-bold mb-0" id="element-count">IFR</p>
                                            @else
                                                <p class="fs-5 text-dark fw-bold mb-0" id="element-count">VFR</p>
                                            @endif
                                        </div>
                                    </div>
                                    <img src="{{ $eventRoute->img_url }}">
                                </div>
                            </div>
                            <div class="row p-2 d-flex justify-content-end">
                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal" data-bs-target="#createLegModal">Edit name
                                </button>
                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal" data-bs-target="#createLegModal">Edit
                                    description
                                </button>
                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal" data-bs-target="#createLegModal">Edit picture
                                </button>
                                <button class="btn btn-sm w-25 btn-soft-danger" data-bs-toggle="modal" data-bs-target="#createLegModal">
                                    Route Löschen
                                </button>
                            </div>
                        </div>

                        <div id="content-container">

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">Aircraft Types
                                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal"
                                                    data-bs-target="#createLegModal">Edit
                                                </button>
                                            </th>
                                            <th class="border-bottom p-3">Flight Rules
                                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal"
                                                    data-bs-target="#createLegModal">Edit
                                                </button>
                                            </th>
                                            <th class="border-bottom p-3">Start (UTC)
                                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal"
                                                    data-bs-target="#createLegModal">Edit
                                                </button>
                                            </th>
                                            <th class="border-bottom p-3">Ende (UTC)
                                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal"
                                                    data-bs-target="#createLegModal">Edit
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tr class="text-center">
                                        <td>{{ $eventRoute->aircrafts }}</td>
                                        <td>
                                            @if ($eventRoute->flight_rules = 'I')
                                                IFR
                                            @else
                                                VFR
                                            @endif
                                        </td>
                                        <td>{{ $eventRoute->begins_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $eventRoute->ends_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                </table>
                                <p class="text-muted mt-2" id="dataset-length"></p>
                            </div>
                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">Legs -
                                                <button class="btn btn-sm btn-soft-primary w-5" data-bs-toggle="modal"
                                                    data-bs-target="#createLegModal">Leg
                                                    hinzufügen
                                                </button>
                                            </th>
                                            <th class="border-bottom p-3">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody id="events-list-content">
                                        @foreach ($eventRoute->legs as $leg)
                                            <tr class="text-center">

                                                <td>
                                                    {{ $leg->departure->icao }} <i class="mdi mdi-send"></i> {{ $leg->arrival->icao }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal"
                                                        data-bs-target="#createLegModal">Edit Leg
                                                    </button>
                                                    <button class="btn btn-sm w-25 btn-soft-danger" data-bs-toggle="modal"
                                                        data-bs-target="#createLegModal">
                                                        Leg Löschen
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="text-muted mt-2" id="dataset-length"></p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
    </div>

    <div class="modal fade" id="createLegModal" tabindex="-1" aria-labelledby="createLegModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="createLegModalLabel">Create Leg</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-leg-form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <lable class="form-label">Departure Aerodrome ICAO</lable>
                                    <span class="text-danger"> *</span>
                                    <div class="form-icon position-relative">
                                        <input type="text" class="form-control" id="leg-departure" name="departure">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <lable class="form-label">Arrival Aerodrome ICAO</lable>
                                    <span class="text-danger"> *</span>
                                    <div class="form-icon position-relative">
                                        <input type="text" class="form-control" id="leg-arrival" name="arrival">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="createLeg()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .daterangepicker {
            color: black !important;
        }

        .fullright {
            position: sticky;
            border-right: 50px;
        }
    </style>
@endsection

@push('custom-script')
    <script>
        function createLeg() {
            let formData = new FormData(document.querySelector('#create-leg-form'));

            axios.post('{{ route('administration.prevent.route.leg.store', $eventRoute->id) }}', formData)
                .then(res => {
                    showNoty('Leg zur Route hinzugefügt.');
                })
        }

        function removeLeg(id) {
            axios.delete('{{ route('administration.prevent.route.leg.delete', $eventRoute->id) }}', {
                data: {
                    leg_id: id
                }
            })
        }

        function editLeg(id) {

        }
    </script>
@endpush
