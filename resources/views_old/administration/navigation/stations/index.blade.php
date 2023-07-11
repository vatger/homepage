@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Stationsverwaltung</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Stationsverwaltung</li>
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
                                            <i class="mdi mdi-radio-tower fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Stationen</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $stations->total() }}</p>
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
                                        <input name="search_string" id="station-search-input" class="form-control ps-5" type="text"
                                            placeholder="Ident, Name, Frequenz">
                                    </div>
                                </li>
                            </div>
                        </div>

                        <div class="p-4 text-center" id="error-container" style="display: none">
                            <div class="alert alert-danger mt-3" role="alert" id="error-message">Ein Fehler ist beim
                                Laden der Daten aufgetreten. Wir probieren es in <span id="error-countdown">60</span>
                                Sekunden automatisch erneut. Der Fehler wurde automatisch an das Web-Department
                                weitergegeben.
                            </div>
                        </div>

                        <div id="content-container">
                            <div class="row p-4 col-lg-4 col-md-4 col-sm-12" style="float: right; max-width: 300px">
                                <button class="btn btn-sm btn-soft-primary">Station hinzufügen</button>
                            </div>

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3 w-25">Ident</th>
                                            <th class="border-bottom p-3">Name</th>
                                            <th class="border-bottom p-3" style="width: 10%">Buchbar</th>
                                            <th class="border-bottom p-3 w-25">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="station-list-content">
                                        <td colspan="4" class="text-center text-muted">Lade Daten...</td>
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>

                                <ul class="pagination mb-0 mt-4" style="display: none">
                                    @if ($stations->lastPage() == 1)
                                        <li class="page-item" data-action="prev"><a class="page-link text-muted" id="page-item-prev"
                                                href="javascript:void(0)">Zurück</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                                href="javascript:void(0)">{{ $stations->currentPage() }}</a>
                                        </li>
                                        <li class="page-item" data-action="next"><a class="page-link text-muted" id="page-item-next"
                                                href="javascript:void(0)">Nächste</a>
                                        </li>
                                    @else
                                        <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev"
                                                href="javascript:void(0)">Zurück</a>
                                        </li>
                                        <li class="page-item" id="page-item-first" data-action="first"><a class="page-link"
                                                href="javascript:void(0)">1</a></li>
                                        <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                                href="javascript:void(0)">{{ $stations->currentPage() }}</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                        <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                                href="javascript:void(0)">{{ $stations->lastPage() }}</a>
                                        </li>
                                        <li class="page-item" data-action="next"><a class="page-link" id="page-item-next"
                                                href="javascript:void(0)">Nächste</a>
                                        </li>
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

    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@push('custom-script')
    <script>
        let linkBaseUrl = "{{ route('administration.navigation.stations') }}";

        const options = {
            currentPage: {{ $stations->currentPage() }},
            maxPage: {{ $stations->lastPage() }},
            ajaxSearchUrl: "{{ route('api.administration.navigation.stations.search') }}",
            loadPaginatedUrl: "{{ route('api.administration.navigation.stations.getpaginated') }}",

            list_content: $("#station-list-content"),
            search_input: $("#station-search-input"),
        };

        new Pagination(
            options,
            (value, container) => {
                if (!container) return;

                container.append(`<tr class="text-center">
                        <td>${value['ident']}</td>
                        <td>${value['name']}</td>
                        <td>${(value['bookable'] === 1) ? `<span class="badge bg-soft-success">Ja</span>` : value['atis'] === 1 ? `<span class="badge bg-soft-secondary">ATIS</span>` : `<span class="badge bg-soft-warning">Nein</span>`}</td>
                        <td>
                            <a href="${linkBaseUrl + '/' + value['id']}"><button class="btn btn-sm btn-soft-primary">Anzeigen</button></a>
                        </td>
                    </tr>`);
            }
        );
    </script>
@endpush
