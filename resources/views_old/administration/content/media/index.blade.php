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
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Mediaverwaltung</li>
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
                                            <h6 class="mb-0 text-muted">Medien</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $media->total() }}</p>
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
                                        <input name="search_string" id="media-search-content" class="form-control ps-5" type="text" placeholder="Name">
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
                                <button class="btn btn-sm btn-soft-primary" onclick="createMedia()">Media hinzufügen</button>
                            </div>

                            <div class="row p-4 pt-0 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            @can('media.update')
                                                <th class="border-bottom p-3">ID</th>
                                            @endcan
                                            <th class="border-bottom p-3">Name</th>
                                            <th class="border-bottom p-3">Link</th>
                                            <th class="border-bottom p-3">Erstellt Am</th>
                                            @can('media.update')
                                                <th class="border-bottom p-3">Erstellt Von</th>
                                            @endcan
                                            <th class="border-bottom p-3">Freigegeben</th>
                                            <th class="border-bottom p-3">Aktionen</th>
                                        </tr>

                                    </thead>
                                    <tbody id="media-list-content">
                                        @can('media.update')
                                            <td colspan="7" class="text-center text-muted">Lade Daten...</td>
                                        @else
                                            <td colspan="5" class="text-center text-muted">Lade Daten...</td>
                                        @endcan
                                    </tbody>
                                </table>

                                <p class="text-muted mt-2" id="dataset-length"></p>

                                <ul class="pagination mb-0 mt-4" style="display: none">
                                    <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev" href="javascript:void(0)">Zurück</a>
                                    </li>
                                    <li class="page-item" id="page-item-first" data-action="first"><a class="page-link" href="javascript:void(0)">1</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                            href="javascript:void(0)">{{ $media->currentPage() }}</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                    <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                            href="javascript:void(0)">{{ $media->lastPage() }}</a></li>
                                    <li class="page-item" data-action="next"><a class="page-link" id="page-item-next"
                                            href="javascript:void(0)">Nächste</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addMediaModal" tabindex="-1" aria-labelledby="createMediaModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="mediaModal-title">Media Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="media-form">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <lable class="form-label">Media Name</lable><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <input type="text" class="form-control" id="media-name" name="mediaName">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Media Datei</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <input name="mediaFiles" id="media-input" class="form-control" type="file" data-form-type="file"
                                                ref="file">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Media License</label><span class="text-danger"> *</span>
                                        <div class="form-icon position-relative">
                                            <input class="form-check-input" type="checkbox" id="media-license" name="mediaLicense" value="1">
                                            Ich versichere, dass das Medium frei von Rechten Dritter ist und somit nicht gegen das Urheberrecht verstößt.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft-secondary" data-dismiss="modal" data-bs-dismiss="modal"
                        data-form-type="other">Schließen</button>
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createMedia-modal-button"
                        onclick="submitMedia()">Hinzufügen</button>
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
        const options = {
            currentPage: {{ $media->currentPage() }},
            maxPage: {{ $media->lastPage() }},
            {{-- ajaxSearchUrl: " {{ route('api.administration.content.media.search') }}", --}}
            loadPaginatedUrl: "{{ route('api.administration.content.media.getpaginated') }}",

            list_content: $("#media-list-content"),
            search_input: $("#media-search-content"),
        };

        function loadMediaData() {
            return new Pagination(
                options,
                (value, container) => {
                    if (!container) return;

                    if (!value['href']) return;

                    container.append(`<tr class="text-center" id="media-id-${value['id']}">` +
                        @can('media.update')
                            `<td id="media-id-${value['id']}"">${value['id']}</td>` +
                        @endcan
                        `<td id="media-name-${value['name']}">${value['name']}</td>
                            <td><a id="media-link-${value['id']}" href="${value['href']}">${value['href']}</a></td>
                            <td id="media-date-${value['id']}">${formatDate(value['created_at'])}</td>` +
                        @can('media.update')
                            `<td>${value['user_id']}</td>` +
                        @endcan
                        `<td>${(value['approved'] === 1) ? `<span class="badge bg-soft-success">Ja</span>` : `<span class="badge bg-soft-warning">Nein</span>`}</td>
                            <td>
                                <div class="btn-group">
                                    ${value['approved'] === 1 ? `<button class="btn btn-sm btn-soft-warning p-1 px-3" onclick="toggleMediaApproved(${value['id']})" style="font-size: 15px"><i class="mdi mdi-comment-check-outline"></i></button>` : `<button class="btn btn-sm btn-soft-success p-1 px-3" onclick="toggleMediaApproved(${value['id']})" style="font-size: 15px"><i class="mdi mdi-comment-check-outline"></i></button><a class="btn btn-sm btn-soft-primary p-1 px-3" href="/administration/content/media/${value['link']}" style="font-size: 15px"><i class="mdi mdi-glasses"></i></a>`}
                                    <button class="btn btn-sm btn-soft-danger p-1 px-3" onclick="deleteMedia(${value['id']})" style="font-size: 15px">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>`);
                }
            );
        }

        let mediaData = loadMediaData();

        let mediaFile;

        function createMedia() {
            $("#createMedia-modal-button").css('display', 'block');

            $("#addMediaModal").modal('show');
        }

        document.getElementById('media-input').addEventListener("change", function(e) {
            mediaFile = this.files[0];
        })

        function submitMedia() {
            let licenseAccepted = document.getElementById('media-license').checked;

            if (!licenseAccepted) {
                showNoty('Du musst das Urheberrecht anerkennen!', 'error');
                return;
            }

            let formData = new FormData();
            formData.append("mediaFile", mediaFile);
            formData.append("mediaLicense", licenseAccepted);
            formData.append("mediaName", document.querySelector('[name="mediaName"]').value)
            axios.post('{{ route('api.administration.content.media.store') }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                showNoty('Mediendatei erfolgreich hinzugefügt.');
                $('#addMediaModal').modal('hide');
                mediaData = loadMediaData();
            })
        }

        function toggleMediaApproved(id) {
            axios.patch('/api/content/media/' + id, {
                    toggleStatus: true
                })
                .then(res => {
                    showNoty(res.data.message);
                    mediaData = loadMediaData();
                });
        }

        function deleteMedia(id) {
            axios.delete('/api/content/media/' + id)
                .then(res => {
                    if (res.data) {
                        showNoty('Mediendatei erfolgreich entfernt.');
                        mediaData = loadMediaData();
                    }
                });
        }
    </script>
@endpush
