@php use Carbon\Carbon; @endphp
@extends('administration.partials.master')
@extends('administration.partials.config')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Routes</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Event</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Tour - Editorpage</li>
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
                                            <h6 class="mb-0 text-muted">Tour editor</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $routes->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="content-container">
                            <div class="row p-4 col-lg-4 col-md-4 col-sm-12" style="float: right; max-width: 300px">
                                <button class="btn btn-sm btn-soft-primary" onclick="createRoute()">Route hinzufügen
                                </button>
                            </div>
                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">Name</th>
                                            <th class="border-bottom p-3">Aktion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="events-list-content">
                                        @foreach ($routes as $r)
                                            @if ($r->visible == '0')
                                                <tr class="text-center">
                                                    <td>{{ $r->name }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="btn group">
                                                            <a href="{{ route('administration.prevent.routedev.show', $r) }}"
                                                                class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px"><i
                                                                    class="mdi mdi-eye-outline"></i></a>
                                                            <button class="btn btn-sm btn-soft-primary" onclick="editRoute(id)">
                                                                <i class="mdi mdi-lead-pencil"></i></button>
                                                            <button class="btn btn-sm btn-soft-danger"
                                                                onclick="removeRoute(' {{ route('administration.prevent.route.delete', $r->id) }} ')">
                                                                X
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="routeModal-title">Route Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="route-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Name</lable>
                                        <span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="route-name" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Description</lable>
                                        <span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <textarea class="form-control" id="route-description" name="description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Aircraft Types</lable>
                                        <span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="route-actypes" name="aircrafts">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Flight Rules</lable>
                                        <span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <select type="text" class="form-control" id="route-flight-rules" name="flight_rules">
                                                <option value="I">I</option>
                                                <option value="V">V</option>
                                                <option value="I+V">I+V</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Visible</lable>
                                        <span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <select type="text" class="form-control" id="route-visible" name="visible">
                                                <option value="1">Ja</option>
                                                <option value="0">Nein</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Link</lable>
                                        </span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="route-link" name="link">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Banner</lable>
                                        </span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="route-img-url" name="img_url">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Begin</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book fea icon-sm icons">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <input name="begins_at" id="route-begin" type="text" class="form-control ps-5"
                                            value="{{ Carbon::now()->format('d.m.Y H:i') }}">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">End</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-book fea icon-sm icons">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <input name="ends_at" id="route-end" type="text" class="form-control ps-5"
                                            value="{{ Carbon::now()->addDays(7)->format('d.m.Y H:i') }}">
                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal"
                        data-form-type="other">Schließen
                    </button>
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createRoute-modal-button" onclick="submitRoute()">Hinzufügen
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoute" tabindex="-1" aria-labelledby="createRouteModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <form id="editRoute">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded shadow border-0">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title" id="routeModal-title">Eventroute bearbeiten</h5>
                        <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                                class="uil uil-times fs-4 text-dark"></i></button>
                    </div>
                    <div class="row ">
                        <div class=" col-4  p-3">
                            <input type="text" class="form-control" id="collective-name" name="name" placeholder="Name">
                        </div>
                        <div class=" col-8  p-3 ">
                            <input type="text" class="form-control" id="stations_search" name="name" placeholder="Kürzel, Name, ...">
                        </div>
                    </div>
                    <div class="row ">
                        <div class=" col-4  p-3">
                            <label class="form-label">Begin</label>
                            <input name="begins_at" id="route-begin" type="text" class="form-control ps-5"
                                value="{{ Carbon::now()->format('d.m.Y H:i') }}">
                            <label class="form-label">End</label>
                            <input name="ends_at" id="route-end" type="text" class="form-control ps-5"
                                value="{{ Carbon::now()->addDays(7)->format('d.m.Y H:i') }}">
                        </div>
                        <div class=" col-8  p-3">
                            <div class="table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">Stationen</th>
                                            <th class="border-bottom p-3">Name</th>
                                            <th class="border-bottom p-3">Aktion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">EDGG_K_CTR</th>
                                            <th class="border-bottom p-3">KTG Kitzingen</th>
                                            <th class="border-bottom p-3">
                                                <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row ">
                        <div class=" col-4  p-3">
                            <div class="table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">ausgewählte Stationen</th>
                                            <th class="border-bottom p-3">Aktion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3">EDGG_K_CTR</th>
                                            <th class="border-bottom p-3">
                                                <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class=" col-4  p-3">
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
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
    </style>
@endsection

@push('custom-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $(function() {
            $('#route-begin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                locale: {
                    format: 'DD.MM.YYYY HH:mm',
                },
                timePicker: true,
                drops: 'auto',
            });

            $('#route-end').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker24Hour: true,
                locale: {
                    format: 'DD.MM.YYYY HH:mm',
                },
                timePicker: true,
                drops: 'auto',
            });
        })

        function createRoute() {
            $("#createRoute-modal-button").css('display', 'block');

            $("#addRouteModal").modal('show');
        }

        function submitRoute() {
            let formData = new FormData(document.querySelector('#route-form'));
            axios.post('{{ route('administration.prevent.route.store') }}', formData)
                .then(res => {
                    showNoty('Route angelegt.');

                })
        }

        function removeRoute(url) {
            axios.delete(url)
                .then(res => {
                    showNoty('Route gelöscht.');
                })

        }

        function editRoute(id) {
            $("#editRoute").modal('show');
        }
    </script>
@endpush
