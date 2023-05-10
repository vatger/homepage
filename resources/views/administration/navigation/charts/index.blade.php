@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Karten</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Navigation</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Karten</li>
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
                                            <i class="mdi mdi-airport fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Karten</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $charts->total() }}</p>
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
                                        <input name="search_string" id="chart-search-input" class="form-control ps-5" type="text"
                                            placeholder="ICAO, IATA, Name">
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
                            <div class="row p-4 col-lg-4 col-md-4 col-sm-12" style="float: right; max-width: 300px">
                                <button class="btn btn-sm btn-soft-primary" id="openAddChartModal" data-bs-toggle="modal"
                                    data-bs-target="#createChartModal">Chart hinzufügen</button>
                            </div>

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3 w-25">Name</th>
                                            <th class="border-bottom p-3 w-25">Erstellt</th>
                                            <th class="border-bottom p-3 w-25">Öffentlich</th>
                                            <th class="border-bottom p-3 w-25">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="chart-list-content">
                                        <td colspan="4" class="text-center text-muted">Lade Daten...</td>
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>

                                <ul class="pagination mb-0 mt-4" style="display: none">
                                    <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev" href="javascript:void(0)">Zurück</a>
                                    </li>
                                    <li class="page-item" id="page-item-first" data-action="first"><a class="page-link" href="javascript:void(0)">1</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                            href="javascript:void(0)">{{ $charts->currentPage() }}</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                    <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                            href="javascript:void(0)">{{ $charts->lastPage() }}</a></li>
                                    <li class="page-item" data-action="next"><a class="page-link" id="page-item-next"
                                            href="javascript:void(0)">Nächste</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="createChartModal" tabindex="-1" aria-labelledby="createChartModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Chart Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="chart-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Chart Name</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <input name="name" id="chartName-input" class="form-control ps-5" placeholder="RNP 25C"
                                                data-form-type="other">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">AIRAC</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <input name="airac" id="chartAirac-input" class="form-control ps-5" placeholder="2213"
                                                data-form-type="other">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Chart Location (The FULL URL to the chart)</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <input name="href" id="chartHref-input" class="form-control ps-5"
                                                placeholder="https://nav.vatsim-germany.org/files/" data-form-type="other">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Chart Type</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <select name="type" id="chartType-input" class="form-control ps-5">
                                                <option value="aoi">AOI</option>
                                                <option value="afc">AFC</option>
                                                <option value="agc">AGC</option>
                                                <option value="apc">APC</option>
                                                <option value="sid">SID</option>
                                                <option value="star">STAR</option>
                                                <option value="iac">IAC</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Chart Flightrule</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <select name="fri" id="chartFri-input" class="form-control ps-5">
                                                <option value="ifr">IFR</option>
                                                <option value="vfr">VFR</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="public_available" id="chartPublished-input" class="form-check-input">
                                        <label for="" class="form-check-label">Shall this chart be public? (If not, only authenticated users
                                            will see it. MUST BE SET FOR VFR CHARTS BY NAVIGRAPH)</label>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="published" id="chartPublished-input" class="form-check-input">
                                        <label for="" class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal"
                        data-form-type="other">Schließen</button>
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createchart-button">Hinzufügen</button>
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
    <script>
        const redirUrl = "{{ route('administration.navigation.charts.view', ['chart' => ':chart']) }}".toString().replace(
            ':chart', '');

        function loadData() {
            const options = {
                currentPage: {{ $charts->currentPage() }},
                maxPage: {{ $charts->lastPage() }},
                ajaxSearchUrl: "{{ route('api.administration.navigation.charts.search') }}",
                loadPaginatedUrl: "{{ route('api.administration.navigation.charts.getpaginated') }}",

                list_content: $("#chart-list-content"),
                search_input: $("#chart-search-input"),
            };

            new Pagination(
                options,
                (value, container) => {
                    if (!container) return;

                    container.append(`<tr class="text-center">
                            <td>${value['name']}</td>
                            <td>${formatDate(value['created_at'])}</td>
                            <td>${(value['published'] === 1) ? `<span class="badge bg-soft-success">Ja</span>` : `<span class="badge bg-soft-warning">Nein</span>` } </td>
                            <td>
                                <a class="btn btn-sm btn-soft-primary" href="${redirUrl + value['id']}">Anzeigen</a>
                            </td>
                        </tr>`);
                }
            );
        }

        loadData();

        $('#createchart-button').on('click', () => {
            formData = new FormData();
            formData = $('#chart-form').serialize();
            axios.post('{{ route('administration.navigation.charts.store') }}', formData)
                .then(res => {
                    showNoty('Chart erfolgreich gespeichert!');
                    loadData();
                });
        });
    </script>
@endpush
