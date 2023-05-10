@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">URL-Kürzer</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.navigation') }}">Contentverwaltung</a>
                        </li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">URL-Kürzer</li>
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
                                            <i class="mdi mdi-xml fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">URLs</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $links->total() }}</p>
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
                                        <input name="search_string" id="url-search-input" class="form-control ps-5" type="text"
                                            placeholder="Kurze URL, Ziel">
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
                                <button class="btn btn-sm btn-soft-primary" onclick="$('#createURLModal').modal('show')">URL hinzufügen</button>
                            </div>

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-bottom p-3" style="width: 20%">Kurze URL</th>
                                            <th class="border-bottom p-3" style="width: 20%">Ziel</th>
                                            <th class="border-bottom p-3" style="width: 20%">Aktiv</th>
                                            <th class="border-bottom p-3" style="width: 20%">Aktiv Bis</th>
                                            <th class="border-bottom p-3" style="width: 20%">Aktion</th>
                                        </tr>

                                    </thead>
                                    <tbody id="url-list-content">
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
                                            href="javascript:void(0)">{{ $links->currentPage() }}</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                    <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                            href="javascript:void(0)">{{ $links->lastPage() }}</a></li>
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

    <div class="modal fade" id="createURLModal" tabindex="-1" aria-labelledby="createRunwayModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Gekürzte URL Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="url-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="syslog-account" class="form-label">Gekürzte URL</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-hash fea icon-sm icons">
                                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                                <line x1="16" y1="3" x2="14" y2="21"></line>
                                            </svg>
                                            <input name="shortLink" id="shortLink-input" class="form-control ps-5" placeholder="vatger"
                                                data-form-type="other" style="text-transform: lowercase" maxlength="70">

                                            <p class="text-muted mt-1 mb-1" id="link-preview-1"></p>
                                            <p class="text-muted pt-0" id="link-preview-2"></p>
                                            <p class="text-danger" id="link-error-container"></p>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6 col-sm-12 px-md-2" style="padding-left: 0 !important;">
                                    <div class="mb-3">
                                        <label for="syslog-date" class="form-label">Ziel (Link)</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-compass fea icon-sm icons">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                            </svg>
                                            <input name="link" id="link-input" class="form-control ps-5" placeholder="https://vatsim-germany.org">
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6 col-sm-12 px-md-2" style="padding-right: 0 !important;">
                                    <div class="mb-3">
                                        <label for="syslog-account" class="form-label">Aktiv</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-maximize-2 fea icon-sm icons">
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <polyline points="9 21 3 21 3 15"></polyline>
                                                <line x1="21" y1="3" x2="14" y2="10"></line>
                                                <line x1="3" y1="21" x2="10" y2="14"></line>
                                            </svg>
                                            <select name="active" id="active-input" class="form-control ps-5">
                                                <option value="1" selected>Ja</option>
                                                <option value="0">Nein</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="col-md-6 col-sm-12 px-md-2" style="padding-left: 0 !important;">
                                    <div class="mb-3">
                                        <label for="syslog-account" class="form-label">Zeitliche Begrenzung</label><span class="text-danger">
                                            *</span>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-maximize-2 fea icon-sm icons">
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <polyline points="9 21 3 21 3 15"></polyline>
                                                <line x1="21" y1="3" x2="14" y2="10"></line>
                                                <line x1="3" y1="21" x2="10" y2="14"></line>
                                            </svg>
                                            <select name="active-until-toggle" id="active-until-toggle-input" class="form-control ps-5">
                                                <option value="1">Ja</option>
                                                <option value="0" selected>Nein</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-md-6 col-sm-12 col-12 px-md-2" style="padding-right: 0 !important;">
                                    <div class="mb-3">
                                        <label for="syslog-account" class="form-label">Aktiv Bis</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-map-pin fea icon-sm icons">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            <input name="active-until" id="active-until-input" class="form-control ps-5"
                                                placeholder="{{ \Carbon\Carbon::now()->addDay()->format('d.m.Y') }}" data-form-type="other">
                                        </div>
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
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createUrl-button">Hinzufügen</button>
                </div>
            </div>
        </div>
    </div>

    <input type="text" id="copy-input" style="display: none">

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
    <script type="text/javascript" src="{{ asset('js/custom/pagination.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        const base_url = window.location.origin
        const altn_url = "https://vatger.de"

        let link_count = 0;

        const options = {
            currentPage: {{ $links->currentPage() }},
            maxPage: {{ $links->lastPage() }},
            ajaxSearchUrl: "{{ route('api.administration.content.url.search') }}",
            loadPaginatedUrl: "{{ route('api.administration.content.url.getpaginated') }}",
            cols: 5,

            list_content: $("#url-list-content"),
            search_input: $("#url-search-input"),
        };

        const pag = new Pagination(
            options,
            (value, container) => {
                if (!container) return;

                appendToContainer(container, value);

                $("[rel='tooltip']").tooltip({
                    html: true
                });
            }
        );

        function convertToLink(lnk) {
            if (lnk.includes('http')) {
                return lnk;
            } else {
                return 'https://' + lnk;
            }
        }

        function toggleActive(id) {
            let badge = $(`#active-indicator-${id}`);

            $.ajax({
                url: "{{ route('api.administration.content.url.toggleActive') }}",
                method: 'PATCH',
                data: {
                    'link_id': id,
                },
                success: (data) => {
                    let res = data['active'];
                    let dateField = $(`#date-url-${id}`)

                    badge.removeClass('bg-soft-success').removeClass('bg-soft-warning').removeClass(
                        'bg-soft-danger');

                    if (res) {
                        if (data['active_until'] === null) {
                            badge.addClass('bg-soft-success');
                            badge.text('Ja');
                            dateField.text('-');
                        } else {
                            badge.addClass('bg-soft-warning');
                            badge.text('Zeitlich Begrenzt');
                            dateField.text(formatDate(data['active_until']).split(', ')[0]);
                        }
                    } else {
                        badge.addClass('bg-soft-danger');
                        badge.text('Nein');
                        dateField.text('-');
                    }

                    $("[rel='tooltip']").tooltip({
                        html: true
                    });
                }
            })
        }

        function deleteUrl(id) {
            $.ajax({
                url: "{{ route('api.administration.content.url.remove') }}",
                method: 'POST',
                data: {
                    'link_id': id
                },
                success: (data) => {
                    $(`#url-id-${id}`).remove();
                    showNoty(data['success']);
                    link_count--;

                    if (link_count === 0) {
                        $("#url-list-content").append(`
                            <tr id="noresult-found">
                                <td colspan="5" class="text-muted text-center">Keine Treffer</td>
                            </tr>
                        `);
                    }

                    pag.updateList(true);
                },
                error: () => {
                    showNoty('Ein Fehler ist aufgetreten. Versuche es bitte erneut.', 'danger');
                }
            });
        }

        $(document).ready(function() {
            $(document).on('click', '.copy-link-button', function() {
                let val = window.location.origin + '/r/' + $(this).data('linkshort');

                if (typeof navigator.clipboard === "undefined") {
                    let $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(val).select();
                    document.execCommand("copy");
                    $temp.remove();
                } else {
                    //TODO: Clipboard API
                }
            })

            $("#shortLink-input").on('keyup', function() {
                // get value from shorturl-input
                let val = $("#shortLink-input").val()
                $("#link-error-container").empty();

                console.log($(this).val())
                if ($(this).val() === "") {
                    $("#link-preview-1").empty();
                    $("#link-preview-2").empty();
                    return;
                }

                $("#link-preview-1").text(base_url + "/r/" + val);
                $("#link-preview-2").text(altn_url + "/r/" + val);
            });

            $("#active-input").on('change', function() {
                let au = $("#active-until-input");
                let aut = $("#active-until-toggle-input");

                if ($(this).val() === "0") {
                    au.attr('disabled', true);
                    aut.attr('disabled', true);
                } else {
                    aut.attr('disabled', false);

                    if (aut.val() !== '0')
                        au.attr('disabled', false);
                }
            });

            $("#active-until-toggle-input").on('change', function() {
                let val = $(this).val();
                let au = $("#active-until-input");

                if (val === "0")
                    au.attr('disabled', true);
                else
                    au.attr('disabled', false);
            });

            // Submit form
            $("#createUrl-button").on('click', () => {
                let formData = $("#url-form").serialize();

                $.ajax({
                    url: "{{ route('api.administration.content.url.create') }}",
                    method: 'POST',
                    data: formData,
                    success: (data) => {
                        appendToContainer(options.list_content, data);

                        pag.updateList(false)

                        $("#createURLModal").modal('hide');
                    },
                    error: (data) => {
                        $("#link-preview-1").empty();
                        $("#link-preview-2").empty();
                        $("#link-error-container").text(data['responseJSON']['error'])
                    }
                })
            });
        })

        function appendToContainer(container, value) {
            const max_str_length = 30;

            link_count++;
            $("#noresult-found").remove();

            container.append(`<tr class="text-center" id="url-id-${value['id']}">
                        <td><a rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-html="true" data-bs-original-title="${base_url + '/r/' + value['shortLink']} <br> oder <br> ${altn_url + '/r/' + value['shortLink']}">/${value['shortLink']}</a></td>
                        <td><a class="link" rel="tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="${convertToLink(value['link'])}" href="${convertToLink(value['link'])}" target="_blank">${value['link'].length > max_str_length ? value['link'].substring(0, max_str_length - 1) + '...' : value['link']}</a></td>
                        <td>${(value['active'] === 1 || value['active'] === '1') ?
                                ((value['active_until']) ?
                                `<span id="active-indicator-${value['id']}" class="badge bg-soft-warning">Zeitlich Begrenzt</span>`
                                : `<span id="active-indicator-${value['id']}" class="badge bg-soft-success">Ja</span>`)
                            : `<span id="active-indicator-${value['id']}" class="badge bg-soft-danger">Nein</span>` }
                        </td>
                        <td id="date-url-${value['id']}">${(value['active_until'] && value['active'] === 1) ? formatDate(value['active_until']).split(", ")[0] : '-'}</td>
                        <td>
                        <div class="btn-group">
                                    <button class="btn btn-sm btn-soft-primary p-1 px-3 copy-link-button" style="font-size: 15px" data-linkshort="${value['shortLink']}"><i class="mdi mdi-content-copy"></i></button>
                                    <button class="btn btn-sm btn-soft-danger p-1 px-3" style="font-size: 15px" onclick="toggleActive(${value['id']})"><i class="mdi mdi-eye-outline"></i></button>
                                    <button class="btn btn-sm btn-soft-danger p-1 px-3" ${value['creator'] === {{ \Illuminate\Support\Facades\Auth::user()->id }} ? '' : 'disabled'} onclick="deleteUrl(${value['id']})" style="font-size: 15px"><i class="mdi mdi-trash-can-outline"></i></button>
                                </div>
                        </td>
                    </tr>`);
        }
    </script>
    <script>
        let date = new Date(Date.parse("{{ \Carbon\Carbon::now()->addDay() }}"));
        let au = $("#active-until-input");

        au.daterangepicker({
            autoApply: true,
            singleDatePicker: true,
            showDropdowns: false,
            locale: {
                format: 'DD.MM.YYYY',
            },
            minDate: date,
            timePicker: false,
        }, function(start, end, label) {
            console.log(start);
        });

        au.attr('disabled', true);
    </script>
@endpush
