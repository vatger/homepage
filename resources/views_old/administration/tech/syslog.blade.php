@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mitgliederverwaltung</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Systemadministration</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Systemlogs</li>
                    </ul>
                </nav>
            </div>

            <div class="row row-container">
                <div class="col mt-4">
                    <div class="card shadow border-0">
                        <div class="row row-container p-4 border-bottom">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <div class="d-flex align-items-center">
                                        <div class="icon text-center rounded-pill">
                                            <i class="mdi mdi-folder-text fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Systemlogs</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $logs->total() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
                                <li class="list-inline-item" style="width: 100%">
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-search fea icon-sm icons">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                        </svg>
                                        <input name="search_string" id="syslog-search-input" class="form-control ps-5" type="text"
                                            placeholder="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">
                                    </div>
                                </li>
                            </div>
                        </div>

                        <div class="p-4 text-center" id="error-container" style="display: none">
                            <div class="alert alert-danger mt-3" role="alert" id="error-message">Ein Fehler ist beim Laden der Daten
                                aufgetreten. Wir probieren es in <span id="error-countdown">60</span> Sekunden automatisch erneut. Der Fehler
                                wurde automatisch an das Web-Department weitergegeben.</div>
                        </div>

                        <div id="content-container">
                            <div class="row row-container p-4 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3 w-25">Typ</th>
                                            <th class="border-bottom p-3 w-25">Pfad</th>
                                            <th class="border-bottom p-3 w-25">Datum</th>
                                            <th class="border-bottom p-3 w-25">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="syslog-list-content">
                                        <td colspan="4" class="text-center text-muted">Lade Daten...</td>
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>

                                <ul class="pagination mb-0 mt-4" style="display: none">
                                    @if ($logs->lastPage() == 1)
                                        <li class="page-item" data-action="prev"><a class="page-link text-muted" id="page-item-prev"
                                                href="javascript:void(0)">Zurück</a></li>
                                        <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                                href="javascript:void(0)">{{ $logs->currentPage() }}</a></li>
                                        <li class="page-item" data-action="next"><a class="page-link text-muted" id="page-item-next"
                                                href="javascript:void(0)">Nächste</a></li>
                                    @else
                                        <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev"
                                                href="javascript:void(0)">Zurück</a></li>
                                        <li class="page-item" id="page-item-first" data-action="first"><a class="page-link"
                                                href="javascript:void(0)">1</a></li>
                                        <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                                href="javascript:void(0)">{{ $logs->currentPage() }}</a></li>
                                        <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                        <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                                href="javascript:void(0)">{{ $logs->lastPage() }}</a>
                                        </li>
                                        <li class="page-item" data-action="next"><a class="page-link" id="page-item-next"
                                                href="javascript:void(0)">Nächste</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="syslog-modal" tabindex="-1" aria-labelledby="LoginForm-title" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Laden...</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-path" class="form-label">Pfad</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="folder" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-path" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-method" class="form-label">Methode</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="cloud" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-method" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Konto</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="user" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-account" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-date" class="form-label">Datum</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="calendar" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-date" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-file" class="form-label">Datei</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-file" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-line" class="form-label">Fehler Zeile</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-line" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="syslog-type" class="form-label">Fehlertyp</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="alert-triangle" class="fea icon-sm icons"></i>
                                        <input disabled name="subject" id="syslog-type" class="form-control ps-5" value="Laden...">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-message" class="form-label">Fehlermeldung</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="message-square" class="fea icon-sm icons"></i>
                                        <textarea disabled name="subject" id="syslog-message" class="form-control ps-5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="syslog-stack" class="form-label">Stack-Trace</label>
                                    <div class="form-icon position-relative">
                                        <i data-feather="file-text" class="fea icon-sm icons"></i>
                                        <textarea disabled name="subject" id="syslog-stack" class="form-control ps-5" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal">Schließen</button>
                    <button type="button" class="btn btn-sm btn-soft-danger">Entfernen</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .row-container {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .modal-dialog {
            max-width: 750px !important;
        }
    </style>
@endsection

@push('custom-script')
    <script>
        let linkBaseUrl = "{{ route('administration.membership.user.view') }}";

        const options = {
            currentPage: {{ $logs->currentPage() }},
            maxPage: {{ $logs->lastPage() }},
            ajaxSearchUrl: "{{ route('api.administration.tech.syslog.search') }}",
            loadPaginatedUrl: "{{ route('api.administration.tech.syslog.getpaginated') }}",

            list_content: $("#syslog-list-content"),
            search_input: $("#syslog-search-input"),
        };

        new Pagination(
            options,
            (value, container) => {
                if (!container) return;

                container.append(`<tr class="text-center">
                        <td>${value['type'].toLowerCase() === 'exception' ? `<span class="badge bg-soft-danger">Exception</span>` : `<span class="badge bg-soft-success">-</span>`}</td>
                        <td>${value['path']}</td>
                        <td>${formatDate(new Date(value['created_at']))}z</td>
                        <td>
                            <a href="javascript:void(0)" class="viewsyslog" data-syslogid="${value['id']}"><button class="btn btn-sm btn-soft-primary">Anzeigen</button></a>
                        </td>
                    </tr>`);
            }
        );


        $(document).on('click', '.viewsyslog', function() {
            let syslogid = $(this).data('syslogid');

            $.ajax({
                url: "{{ route('api.administration.tech.syslog.getinfo') }}",
                type: 'GET',
                data: {
                    syslogid: syslogid
                },
                success: (data) => {
                    $("#syslog-path").val(data['path']);
                    $("#syslog-method").val(data['method']);
                    data['account_id'] ? $("#syslog-account").val(data['account_id']) : $(
                        "#syslog-account").val("-");
                    $("#syslog-date").val(formatDate(new Date(data['created_at'])));
                    $("#syslog-line").val(data['line']);
                    $("#syslog-type").val(data['type']);
                    data['message'] ? $("#syslog-message").val(data['message']) : $("#syslog-message").val(
                        '-');
                    $("#syslog-stack").val(data['stack_trace']);
                    $("#syslog-file").val(data['file']);
                },
                error: () => {}
            });

            $("#syslog-modal").modal('show');
        });
    </script>
@endpush
