<div class="card shadow border-0">
    <div class="row row-custom p-4 border-bottom">
        <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center">
                    <div class="icon text-center rounded-pill">
                        <i class="mdi mdi-road-variant fs-4 mb-0"></i>
                    </div>
                    <div class="flex-1 ms-3">
                        <h6 class="mb-0 text-muted">Pisten</h6>
                        <p class="fs-5 text-dark fw-bold mb-0" id="runway-count">{{ $aerodrome->runways->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
            <li class="list-inline-item" style="width: 100%">
                <div class="form-icon position-relative" data-form-type="search">
                    <button class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#createRunwayModal">Piste
                        Hinzufügen</button>
                </div>
            </li>
        </div>
    </div>

    <div id="content-container" class="px-2">
        <div class="row p-4 table-responsive">
            <table class="table table-center bg-white mb-0">
                <thead>
                    <tr class="text-center">
                        <th class="border-bottom p-3 w-25">Ident</th>
                        <th class="border-bottom p-3 w-25">Heading</th>
                        <th class="border-bottom p-3 w-25">Bodenbelag</th>
                        <th class="border-bottom p-3 w-25">Aktion</th>
                    </tr>

                </thead>
                <tbody id="runway-tablecontent" data-isempty="{{ $aerodrome->runways->count() == 0 ? 'true' : 'false' }}">
                    @if ($aerodrome->runways->count() != 0)
                        @foreach ($aerodrome->runways as $rwy)
                            <tr class="text-center" id="rwy-id-{{ $rwy->id }}">
                                <td>{{ strtoupper($rwy->ident) }}</td>
                                <td>{{ $rwy->heading }}°</td>
                                <td>{{ $rwy->surfaceTypeString }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-soft-primary p-1 px-3" data-bs-toggle="modal"
                                            data-bs-target="#editRunwayModal{{ $rwy->id }}" style="font-size: 15px"><i
                                                class="mdi mdi-square-edit-outline"></i></button>
                                        <button class="btn btn-sm btn-soft-danger p-1 px-3" onclick="deleteRunway({{ $rwy->id }})"
                                            style="font-size: 15px"><i class="mdi mdi-trash-can-outline"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="4" class="text-muted">Keine Pisten für {{ $aerodrome->icao }} definiert.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createRunwayModal" tabindex="-1" aria-labelledby="createRunwayModalLabel" style="display: none;" aria-hidden="true"
    role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="tsmodal-title">Piste Hinzufügen</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                        class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <div class="modal-body">
                <div class="bg-white px-3 rounded box-shadow">
                    <form id="runway-form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Piste Ident</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-hash fea icon-sm icons">
                                            <line x1="4" y1="9" x2="20" y2="9"></line>
                                            <line x1="4" y1="15" x2="20" y2="15"></line>
                                            <line x1="10" y1="3" x2="8" y2="21"></line>
                                            <line x1="16" y1="3" x2="14" y2="21"></line>
                                        </svg>
                                        <input name="rwyIdent" id="rwyident-input" class="form-control ps-5" placeholder="25C"
                                            data-form-type="other" style="text-transform: uppercase">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-date" class="form-label">Heading</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-compass fea icon-sm icons">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                                        </svg>
                                        <input name="rwyHdg" id="rwyhdg-input" class="form-control ps-5" placeholder="254"
                                            data-form-type="date">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Breite (Meter)</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-maximize-2 fea icon-sm icons">
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <polyline points="9 21 3 21 3 15"></polyline>
                                            <line x1="21" y1="3" x2="14" y2="10"></line>
                                            <line x1="3" y1="21" x2="10" y2="14"></line>
                                        </svg>
                                        <input name="rwyWidth" id="rwywidth-input" class="form-control ps-5" placeholder="60"
                                            data-form-type="other">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="syslog-date" class="form-label">Länge (Meter)</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-maximize-2 fea icon-sm icons">
                                            <polyline points="15 3 21 3 21 9"></polyline>
                                            <polyline points="9 21 3 21 3 15"></polyline>
                                            <line x1="21" y1="3" x2="14" y2="10"></line>
                                            <line x1="3" y1="21" x2="10" y2="14"></line>
                                        </svg>
                                        <input name="rwyLength" id="rwylength-input" class="form-control ps-5" placeholder="4000"
                                            data-form-type="date">
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-md-6 col-sm-12 col-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Bodenbelag</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-truck fea icon-sm icons">
                                            <rect x="1" y="3" width="15" height="13"></rect>
                                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                        </svg>
                                        <select name="rwyType" id="rwyttype" class="form-control ps-5">
                                            <option value="1">Asphalt</option>
                                            <option value="2">Beton</option>
                                            <option value="3">Gras</option>
                                            <option value="4">Wasser</option>
                                            <option value="5">Sand</option>
                                            <option value="6">Graded / Rolled Earth</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="mb-3">
                                    <label for="syslog-account" class="form-label">Koordinaten Threshold</label>
                                    <div class="form-icon position-relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-map-pin fea icon-sm icons">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <input name="rwyThreshold" id="rwythreshold-input" class="form-control ps-5"
                                            placeholder="N050.09.45.739:E009.04.24.508" data-form-type="other">
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
                <button type="button" class="btn btn-sm btn-soft-primary" id="createrwy-button">Hinzufügen</button>
            </div>
        </div>
    </div>
</div>

@push('custom-script')
    <script>
        let runway_count = parseInt($("#runway-count").text());

        function deleteRunway(rwyId) {
            const rwyTC = $("#runway-tablecontent");

            let url =
                "{{ route('administration.navigation.runways.delete', ['aerodrome' => $aerodrome, 'runway' => ':rwyId']) }}";
            url = url.replace(':rwyId', rwyId);
            $.ajax(url, {
                method: 'DELETE',
                success: function() {
                    $(`#rwy-id-${rwyId}`).remove();

                    if (runway_count === 1) {
                        rwyTC.append(`
                            <tr class="text-center">
                                <td colspan="4" class="text-muted">Keine Pisten für {{ $aerodrome->icao }} definiert.</td>
                            </tr>
                        `);
                    }

                    runway_count--;
                    $("#runway-count").text(runway_count);
                    showNoty('Piste erfolgreich gelöscht', 'success', 700);
                },
                error: () => {
                    showNoty('Ein Fehler ist aufgetreten. Versuche es bitte später erneut.', 'danger');
                }
            });
        }

        $("#createrwy-button").on("click", () => {
            const rwyTC = $("#runway-tablecontent");

            $.ajax({
                url: '{{ route('api.administration.navigation.aerodromes.runways.store') }}',
                method: 'POST',
                data: $("#runway-form").serialize() + "&adid={{ $aerodrome->id }}",
                success: (data) => {

                    if (!data) {
                        showNoty('Ein Fehler ist aufgetreten. Versuche es bitte erneut.', 'danger');
                        return;
                    } else {
                        showNoty(`Piste ${data['ident']} erfolgreich hinzugefügt`, 'success', 700)
                    }

                    if (runway_count === 0) {
                        rwyTC.empty();
                    }

                    rwyTC.append(`
                        <tr class="text-center" id="rwy-id-${data['id']}">
                            <td>${data['ident'].toUpperCase()}</td>
                            <td>${data['heading']}°</td>
                            <td>${data['surfaceTypeString']}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-soft-primary p-1 px-3" data-bs-toggle="modal" data-bs-target="#editRunwayModal" style="font-size: 15px"><i class="mdi mdi-square-edit-outline"></i></button>
                                    <button class="btn btn-sm btn-soft-danger p-1 px-3" onclick="deleteRunway(${data['id']})" style="font-size: 15px"><i class="mdi mdi-trash-can-outline"></i></button>
                                </div>
                            </td>
                        </tr>`);

                    runway_count++;
                    $("#runway-count").text(runway_count);
                },
                error: (error, xhr, x) => {

                }
            });
        });
    </script>
@endpush
