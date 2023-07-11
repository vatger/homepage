@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Routes - {{ $eventRoute->name }} legs</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize">Event</li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.prevent.route') }}">Routes</a></li>
                        <li class="breadcrumb-item text-capitalize " aria-current="page">{{ $eventRoute->name }}</li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">legs</li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col mt-4">
                    <div class="card shadow border-0">
                        <div class="row p-4 border-bottom">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
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
                                            <h6 class="mb-0 text-muted">Pilots</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ count($eventRoute->accounts) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="content-container">
                            <div class="row p-4 d-flex justify-content-end">
                                <button class="btn btn-sm btn-soft-primary w-25" data-bs-toggle="modal" data-bs-target="#createLegModal">Leg
                                    hinzufügen
                                </button>
                            </div>
                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">DEPA</th>
                                            <th class="border-bottom p-3">DEST</th>
                                            <th class="border-bottom p-3">Aktion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="events-list-content">
                                        @foreach ($eventRoute->legs as $leg)
                                            <tr class="text-center">
                                                <td>{{ $leg->departure->icao }}</td>
                                                <td>{{ $leg->arrival->icao }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-soft-primary " onclick="editLeg(id)"><i
                                                            class="mdi mdi-lead-pencil"></i></button>
                                                    <button class="btn btn-sm btn-soft-danger " onclick="removeLeg({{ $leg->id }})">X
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p class="text-muted mt-2" id="dataset-length"></p>
                            </div>
                            {{--
                                        <div id="legs-content-{{ $leg->id }}" class="accordion-collapse border-0 collapse" aria-labelledby="legs-{{ $leg->id }}" data-bs-parent="#leg-section" style="">
                                                <div class="accordion-body text-muted">
                                                    <div class="row p-4 pt-0 table-responsive">
                                                        <table class="table table-center bg-white mb-0">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th class="border-bottom p-3">Account</th>
                                                                </tr>
                                                            </thead>
                                                                <tbody id="events-list-content">
                                                                    @foreach ($leg->accounts as $acc)
                                                                        <tr>
                                                                            <td>{{ $acc->id }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                    </div>
                                                </div>
                                            </div>
                                                    --}}

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
