@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Bannergenerator</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Event</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Bannergenerator</li>
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
                                        <div class="flex-1 ms-3">
                                            <h4 class="mb-0 text-muted">Hintergrundbild wählen</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                            <div class=" col-3  p-3">
                                <img class="card-img-top" src="https://vatsim-germany.org/resources/image/102" alt="Card image cap">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mt-4">
                                <div class="card shadow border-0">
                                    <div class="row p-4 border-bottom">
                                        <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-1 ms-3">
                                                        <h4 class="mb-0 text-muted">Vorlage wählen</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class=" col-3 p-5">
                                            <img class="card-img-top" src="https://cpt.vatsim-germany.org/templategen.php?tp=3" alt="Card image cap">
                                        </div>
                                        <div class=" col-3 p-5">
                                            <img class="card-img-top" src="https://cpt.vatsim-germany.org/templategen.php?tp=1" alt="Card image cap">
                                        </div>
                                        <div class=" col-3 p-5">
                                            <img class="card-img-top" src="https://cpt.vatsim-germany.org/templategen.php?tp=2" alt="Card image cap">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mt-4">
                                    <div class="card shadow border-0">
                                        <div class="row p-4 border-bottom">
                                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-1 ms-3">
                                                            <h4 class="mb-0 text-muted">Text Template
                                                                <div class="form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                                        id="flexSwitchTexttemplate" checked>
                                                                </div>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mt-4">
                                    <div class="card shadow border-0">
                                        <div class="row p-4 border-bottom">
                                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-1 ms-3">
                                                            <h4 class="mb-0 text-muted">Bannerdetails</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="bg-white px-3 rounded box-shadow">
                                                <form id="CPTGen">
                                                    <div class="p-4">
                                                        <lable class="form-label">Stationskürzel<input type="text" class="form-control"
                                                                id="Stationskurz" name="name">
                                                        </lable>
                                                    </div>
                                                    <div class="p-4">
                                                        <lable class="form-label">Name<input type="text" class="form-control" id="Name"
                                                                name="name">
                                                        </lable>
                                                    </div>
                                                    <div class="p-4">
                                                        <lable class="form-label">Stationsname<input type="text" class="form-control"
                                                                id="Stationname" name="name">
                                                        </lable>
                                                    </div>
                                                    <div class="p-4">
                                                        <lable class="form-label">Datum<input name="begins_at" id="CPT-begin" type="text"
                                                                class="form-control ps-5" value="{{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}">
                                                        </lable>
                                                    </div>
                                                </form>
                                                <button type="button" class="btn btn-success" id="createCPTBanner" onclick="GenerateCPT()">Generate
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="GenerateCPTpopup" tabindex="-1" aria-labelledby="createRouteModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="routeModal-title">CPT Generator</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="row ">
                    <div class=" col-12  p-3">
                        <img class="card-img-top"
                            src="https://cpt.vatsim-germany.org/gen.php?sc=EDDF_N_APP&sn=Langen%20Radar&tn=Arthur&dt=20.07.2022&ts=1800&te=1900&bg=100&tp=1"
                            alt="Card image cap">
                    </div>
                    <div class=" col-12  p-3">
                        <h6 class="">
                            https://cpt.vatsim-germany.org/gen.php?sc=EDDF_N_APP&sn=Langen%20Radar&tn=Arthur&dt=20.07.2022&ts=1800&te=1900&bg=100&tp=1
                        </h6>
                    </div>
                    <div class=" col-12  p-3">
                        <h3 class=""><?php echo '[20 JUL 2022 | 1800z - 1900z] EDDF_N_APP CPT Arthur'; ?></h3>
                    </div>
                    <div class=" col-12  p-3">
                        <h6 class="">Heute will <p id="nameout"></p> zeigen, dass er auf dem Frankfurter
                            Approach alles unter Kontrolle hat. Unterstützt ihn mit einem Flug von oder nach Frankfurt,
                            egal ob VFR oder IFR.

                            Viel Spaß Arthur! :cool:
                        </h6>
                    </div>
                    <div class=" col-12  p-3">
                        <button type="button" class="btn btn-soft-danger"><a href="https://board.vatsim-germany.org/forums/events.7/">
                                Hier zum Forum</a>
                        </button>
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
            $('#CPT-begin').daterangepicker({
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

        function GenerateCPT() {
            $("#createCPTBanner").css('display', 'block');
            $("#GenerateCPTpopup").modal('show');
            CPTGeneverything();
        }

        function CPTGeneverything() {
            let name = document.getElementById("Name").textContent;


        }
    </script>
@endpush
