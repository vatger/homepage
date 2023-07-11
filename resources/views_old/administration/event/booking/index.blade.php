@php use Carbon\Carbon; @endphp
@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sammelbuchungen</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Event</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Sammelbuchungen</li>
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
                                            <h6 class="mb-0 text-muted">Events</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $events->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div id="content-container">
                            <div class="row p-4 col-lg-4 col-md-4 col-sm-12" style="float: right; max-width: 300px">
                                <button class="btn btn-sm btn-soft-primary" onclick="createCollectiveBooking()">Buchung
                                    hinzufügen
                                </button>
                            </div>
                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3" style="width: 20%">Name</th>
                                            <th class="border-bottom p-3" style="width: 20%">Start (UTC)</th>
                                            <th class="border-bottom p-3" style="width: 20%">Ende (UTC)</th>
                                            <th class="border-bottom p-3" style="width: 20%">Stationen</th>
                                            <th class="border-bottom p-3" style="width: 20%">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="events-list-content">
                                        @foreach ($collectivebookings as $booking)
                                            <tr class="text-center">
                                                <td>{{ $booking->name }}</td>
                                                <td>{{ $booking->starttime }}</td>
                                                <td>{{ $booking->endtime }}</td>
                                                <td> {{ $booking->stations }}</td>
                                                <td>
                                                    <a href="/administration/event/booking/{{ $booking->id }}">
                                                        <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px"><i
                                                                class="mdi mdi-eye-outline"></i></button>
                                                    </a>
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
                <!--end col-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="routeModal-title">Sammelbuchung erstellen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="row ">
                    <div class=" col-4  p-3">
                        <input type="text" class="form-control" id="collective-name" name="name" placeholder="Name">
                    </div>
                    <div class=" col-8  p-3 ">
                        <input wire:model="search" type="text" class="form-control" id="stations_search" name="name"
                            placeholder="Kürzel, Name, ...">
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
                                @foreach ($stations as $s)
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ $s->ident }}</td>
                                            <td>{{ $s->name }}</td>
                                            <td class="border-bottom p-3">
                                                <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>

                    </div>
                </div>
                <div class="row ">
                    <div class=" col-4  p-3">

                    </div>
                    <div class=" col-4  p-3">
                    </div>
                </div>
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

        function createCollectiveBooking() {
            $("#createRoute-modal-button").css('display', 'block');

            $("#addRouteModal").modal('show');
        }
    </script>
@endpush
