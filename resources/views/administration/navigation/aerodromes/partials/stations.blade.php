<div class="card border-0 shadow mt-4">
    <div class="row row-custom p-4 border-bottom">
        <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center">
                    <div class="icon text-center rounded-pill">
                        <i class="mdi mdi-radio-tower fs-4 mb-0"></i>
                    </div>
                    <div class="flex-1 ms-3">
                        <h6 class="mb-0 text-muted">Stationen</h6>
                        <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $aerodrome->stations->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
            <li class="list-inline-item" style="width: 100%">
                <div class="form-icon position-relative" data-form-type="search">
                    <button class="btn btn-sm btn-soft-primary" data-bs-toggle="modal" data-bs-target="#createStationModal">Station
                        Hinzufügen</button>
                </div>
            </li>
        </div>
    </div>
    <div id="content-container" class="px-2 overflow-hidden">
        <div class="row p-4 table-responsive overflow-hidden">
            <table class="table overflow-hidden">
                <thead>
                    <tr>
                        <th>Reihenfolge</th>
                        <th>Ident</th>
                        <th>Name</th>
                        <th class="text-center">Frequenz</th>
                        <th class="text-center">Buchbar</th>
                    </tr>
                </thead>
                <tbody id="station-table">
                    @if ($aerodrome->stations->count() != 0)
                        @foreach ($aerodrome->stations as $s)
                            <tr id="{{ $s->id }}">
                                <td>{{ $s->pivot->order + 1 }}</td>
                                <td>{{ $s->ident }}</td>
                                <td>{{ $s->name }}</td>
                                <td class="text-center">{{ $s->fixedFrequency }}</td>
                                <td class="text-center">
                                    @if (!$s->atis)
                                        <span class="badge bg-soft-success">Ja</span>
                                    @else
                                        <span class="badge bg-soft-secondary">ATIS</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="5" class="text-muted">Keine Stationen gehören zu {{ $aerodrome->icao }}.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="createStationModal" tabindex="-1" aria-labelledby="createStationModalLabel" style="display: none;" aria-hidden="true"
        role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tsmodal-title">Station Hinzufügen</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal" data-form-type="other"><i
                            class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="bg-white px-3 rounded box-shadow">
                        <form id="station-form">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="mb-3">
                                        <label for="newStation" class="form-label">Station</label>
                                        <div class="form-icon position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-truck fea icon-sm icons">
                                                <rect x="1" y="3" width="15" height="13"></rect>
                                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                            </svg>
                                            <select name="newStation" id="newStation" class="form-control ps-5">
                                                @foreach ($stations as $s)
                                                    <option value="{{ $s->id }}">{{ $s->ident }}</option>
                                                @endforeach
                                            </select>
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
                    <button type="button" class="btn btn-sm btn-soft-primary" id="createstation-button">Hinzufügen</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ui-sortable:hover {
        cursor: move !important;
    }

    .ui-state-highlight {
        background-color: #eaeaea !important;
    }
</style>

@push('custom-script')
    <script src="//code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script>
        $("#station-table").sortable({
            placeholder: 'ui-state-highlight',
            update: function(event, ui) {}
        });

        $("#station-table").on("sortupdate", (event, ui) => {
            let stationOrder = $("#station-table").sortable("toArray");

            let count = 1;
            for (let i = 0; i < stationOrder.length; i++) {
                $(`#${stationOrder[i]}`).children('td:first').text(count);
                count++;
            }

            // POST to server using $.post or $.ajax
            $.ajax({
                data: {
                    "order": stationOrder
                },
                type: 'PATCH',
                url: '{{ route('administration.navigation.aerodromes.updateStationOrder', ['aerodrome' => $aerodrome->id]) }}',
                success: function(data) {
                    showNoty('Reihenfolge erfolgreich angepasst', 'success', 700);
                },
                error: () => {
                    location.reload();
                }
            });
        });

        $("#createstation-button").on("click", () => {
            axios.post(
                '{{ route('administration.navigation.aerodromes.station.add', ['aerodrome' => $aerodrome->id]) }}', {
                    newStation: $("#newStation").val()
                }).then(res => {
                if (res.data) {
                    showNoty('Station hinzugefügt');
                    location.reload();
                }
            })
        });
    </script>
@endpush
