@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Partnerverwaltung</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Contentverwaltung</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Partnerverwaltung</li>
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
                                            <i class="mdi mdi-account-heart-outline fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Partner</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $partners->total() }}</p>
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
                                        <input name="search_string" id="partner-search-content" class="form-control ps-5" type="text"
                                            placeholder="Name">
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
                                <button class="btn btn-sm btn-soft-primary" onclick="createPartner()">Partner hinzufügen</button>
                            </div>

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3" style="width: 25%">Name</th>
                                            <th class="border-bottom p-3" style="width: 25%">Link</th>
                                            <th class="border-bottom p-3" style="width: 25%">Erstellt Am</th>
                                            <th class="border-bottom p-3" style="width: 25%">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="partner-list-content">
                                        <td colspan="5" class="text-center text-muted">Lade Daten...</td>
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>

                                <ul class="pagination mb-0 mt-4" style="display: none">
                                    <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev" href="javascript:void(0)">Zurück</a>
                                    </li>
                                    <li class="page-item" id="page-item-first" data-action="first"><a class="page-link" href="javascript:void(0)">1</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                            href="javascript:void(0)">{{ $partners->currentPage() }}</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                    <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                            href="javascript:void(0)">{{ $partners->lastPage() }}</a>
                                    </li>
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

    <div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="createRunwayModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="partnerModal-title">Partner Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="partner-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="syslog-account" class="form-label">Name</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <input name="name" id="partner-name-input" class="form-control ps-5" placeholder="Aerosoft"
                                                data-form-type="other" maxlength="70">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="syslog-date" class="form-label">Link</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-compass fea icon-sm icons">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                            </svg>
                                            <input name="link" id="partner-link-input" class="form-control ps-5" placeholder="https://aerosoft.de">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="syslog-date" class="form-label">Bild</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-compass fea icon-sm icons">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                            </svg>
                                            <input name="link" id="partner-image-input" class="form-control ps-5"
                                                placeholder="https://vatsim-germany.org">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-12 col-sm-12">
                                    <label for="syslog-date" class="form-label">Text</label><span class="text-danger"> *</span>
                                    <ul class="nav nav-pills nav-justified flex-column flex-sm-row" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-cloud-tab" data-bs-toggle="pill" href="#text-de-pill" role="tab"
                                                aria-controls="pills-cloud" aria-selected="true">
                                                <div class="text-center py-1">
                                                    <h6 class="mb-0">Deutsch</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->

                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-smart-tab" data-bs-toggle="pill" href="#text-en-pill" role="tab"
                                                aria-controls="pills-smart" aria-selected="false">
                                                <div class="text-center py-1">
                                                    <h6 class="mb-0">Englisch</h6>
                                                </div>
                                            </a>
                                            <!--end nav link-->
                                        </li>
                                        <!--end nav item-->
                                    </ul>
                                    <!--end nav pills-->

                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="text-de-pill" role="tabpanel" aria-labelledby="text-de-pill">
                                            <textarea name="text_de" id="partner-text-de-input" placeholder="Deutscher Text"></textarea>
                                        </div>
                                        <!--end teb pane-->

                                        <div class="tab-pane fade" id="text-en-pill" role="tabpanel" aria-labelledby="text-en-pill">
                                            <textarea name="text_en" id="partner-text-en-input" placeholder="Englischer Text"></textarea>
                                        </div>
                                        <!--end teb pane-->
                                    </div>
                                    <!--end tab content-->
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
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createPartner-modal-button"
                        onclick="submitPartner()">Hinzufügen</button>
                    <button type="button" class="btn btn-sm btn-soft-primary" id="editPartner-modal-button" style="display: none"
                        onclick="submitPartner()">Bearbeiten</button>
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
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        const inputs = [{
                type: 'name',
                target: $("#partner-name-input"),
            },
            {
                type: 'link',
                target: $("#partner-link-input"),
            },
            {
                type: 'image',
                target: $("#partner-image-input"),
            },
            {
                type: 'text_de',
                target: $("#partner-text-de-input"),
            },
            {
                type: 'text_en',
                target: $("#partner-text-en-input"),
            }
        ];
        const link_max_len = 30;

        const tinySettings = config.tinyMce.admin_reduced;

        const options = {
            currentPage: {{ $partners->currentPage() }},
            maxPage: {{ $partners->lastPage() }},
            ajaxSearchUrl: "{{ route('api.administration.content.partner.search') }}",
            loadPaginatedUrl: "{{ route('api.administration.content.partner.getpaginated') }}",

            list_content: $("#partner-list-content"),
            search_input: $("#partner-search-content"),
        };

        const pag = new Pagination(
            options,
            (value, container) => {
                if (!container) return;

                if (!value['link_url']) return;

                container.append(`<tr class="text-center" id="partner-id-${value['id']}">
                        <td id="p-name-${value['id']}">${value['name']}</td>
                        <td><a id="p-link-${value['id']}" href="${value['link_url']}">${value['link_url'].length > link_max_len ? value['link_url'].substring(0, 30) + "..." : value['link_url']}</a></td>
                        <td id="p-date-${value['id']}">${formatDate(value['created_at'])}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px" onclick="editPartner(${value['id']})"><i class="mdi mdi-clipboard-edit-outline"></i></button>
                                <button class="btn btn-sm btn-soft-danger p-1 px-3" onclick="deletePartner(${value['id']})" style="font-size: 15px"><i class="mdi mdi-trash-can-outline"></i></button>
                            </div>
                        </td>
                    </tr>`);
            }
        );

        init();

        let currentPartner = -1;

        function init() {
            for (let i = 0; i < inputs.length; i++) {
                let idx = ['text_de', 'text_en'].indexOf(inputs[i].type);
                if (idx === 0) {
                    tinySettings.selector = '#partner-text-de-input';
                    tinymce.init(tinySettings);
                } else if (idx === 1) {
                    tinySettings.selector = '#partner-text-en-input';
                    tinymce.init(tinySettings);
                }
            }
        }

        function createPartner() {
            // Set some vars
            currentPartner = -1;

            $("#partnerModal-title").text("Partner Hinzufügen");
            for (let i = 0; i < inputs.length; i++) {
                if (['text_de', 'text_en'].indexOf(inputs[i].type) !== -1) {
                    continue;
                }
                inputs[i].target.val("");

                tinymce.get("partner-text-de-input").setContent("");
                tinymce.get("partner-text-en-input").setContent("");
            }

            $("#createPartner-modal-button").css('display', 'block');
            $("#editPartner-modal-button").css('display', 'none');

            $("#addPartnerModal").modal('show');
        }

        function editPartner(id) {
            // Set some vars
            currentPartner = id;

            $("#partnerModal-title").text("Partner Bearbeiten");
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].target.val("Laden...");
                inputs[i].target.attr('disabled', true);
            }

            tinymce.get('partner-text-de-input').setMode('readonly');
            tinymce.get('partner-text-en-input').setMode('readonly');

            $("#createPartner-modal-button").css('display', 'none');
            $("#editPartner-modal-button").css('display', 'block');

            $("#addPartnerModal").modal('show');

            $.ajax({
                url: "{{ route('api.administration.content.partner.find') }}",
                data: {
                    id: id,
                },
                success: (data) => {
                    for (let i = 0; i < inputs.length; i++) {
                        switch (inputs[i].type) {
                            case 'name':
                                inputs[i].target.val(data['name']);
                                inputs[i].target.attr('disabled', false);
                                break;
                            case 'link':
                                inputs[i].target.val(data['link_url']);
                                inputs[i].target.attr('disabled', false);
                                break;
                            case 'image':
                                inputs[i].target.val(data['logo_url']);
                                inputs[i].target.attr('disabled', false);
                                break;
                            case 'text_de':
                                tinymce.get('partner-text-de-input').setContent(data['description_de']);
                                tinymce.get('partner-text-de-input').setMode('design');
                                break;
                            case 'text_en':
                                tinymce.get('partner-text-en-input').setContent(data['description_en']);
                                tinymce.get('partner-text-en-input').setMode('design');
                                break;
                        }
                    }
                },
                error: () => {
                    $("#addPartnerModal").modal('hide');
                    showNoty('Ein Fehler ist beim Laden der Daten aufgetreten. Versuche es bitte erneut.',
                        'error');
                }
            })

        }

        function submitPartner() {
            let dat = {
                id: currentPartner,
                name: $("#partner-name-input").val(),
                link: $("#partner-link-input").val(),
                image: $("#partner-image-input").val(),
                text_de: tinymce.get('partner-text-de-input').getContent(),
                text_en: tinymce.get('partner-text-en-input').getContent()
            }

            $.ajax({
                url: "{{ route('api.administration.content.partner.submit') }}",
                method: 'POST',
                data: dat,
                success: (data) => {
                    if (currentPartner === -1) {
                        $("#partner-list-content").append(`<tr class="text-center" id="partner-id-${data['id']}">
                        <td id="p-name-${data['id']}">${data['name']}</td>
                        <td><a id="p-link-${data['id']}" href="${data['link_url']}">${data['link_url'].length > link_max_len ? data['link_url'].substring(0, 30) + "..." : data['link_url']}</a></td>
                        <td id="p-date-${data['id']}">${formatDate(data['created_at'])}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px" onclick="editPartner(${data['id']})"><i class="mdi mdi-clipboard-edit-outline"></i></button>
                                <button class="btn btn-sm btn-soft-danger p-1 px-3" onclick="deletePartner(${data['id']})" style="font-size: 15px"><i class="mdi mdi-trash-can-outline"></i></button>
                            </div>
                        </td>
                    </tr>`);
                        showNoty(`Der Partner ${data['name']} wurde erfolgreich hinzugefügt.`);
                    } else {
                        $(`#p-name-${data['id']}`).text(data['name']);
                        $(`#p-link-${data['id']}`).text(data['link_url'].length > link_max_len ? data['link_url']
                            .substring(0, 30) + "..." : data['link_url']);
                        $(`#p-link-${data['id']}`).attr('href', data['link_url']);

                        showNoty(`Der Partner ${data['name']} wurde erfolgreich bearbeitet.`);
                    }

                    $("#addPartnerModal").modal('hide');

                    pag.update('add');
                },
                error: () => {
                    showNoty('Ein Fehler ist aufgetreten. Versuche es bitte erneut.', 'error');
                }
            });
        }

        function deletePartner(id) {
            $.ajax({
                url: "{{ route('api.administration.content.partner.remove') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: (data) => {
                    showNoty('Partner wurde erfolgreich entfernt');

                    pag.update();
                },
                error: () => {
                    showNoty('Ein Fehler ist aufgetreten. Versuche es bitte erneut.', 'error');
                }
            });
        }
    </script>
@endpush
