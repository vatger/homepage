<div class="card border-0 shadow p-4 mt-4">
    <div class="row row-custom p-4 border-bottom">
        <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
            <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center">
                    <div class="icon text-center rounded-pill">
                        <i class="mdi mdi-radio-tower fs-4 mb-0"></i>
                    </div>
                    <div class="flex-1 ms-3">
                        <h6 class="mb-0 text-muted">Charts</h6>
                        <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ $aerodrome->charts->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
            <li class="list-inline-item" style="width: 100%">
                <div class="form-icon position-relative" data-form-type="search">
                    <button id="toggleChartfoxButton"
                        class="btn btn-sm {{ $aerodrome->useChartfox ? 'btn-soft-success' : 'btn-soft-danger' }}">{{ $aerodrome->useChartfox ? 'ChartFox WIRD Genutzt' : 'ChartFox wird nicht genutzt' }}</button>
                </div>
            </li>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center mt-4 table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>AIRAC</th>
                        <th>Last Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chartsCombined as $chart)
                        @if (is_array($chart))
                            <tr>
                                <td>{{ Str::upper($chart['type']) }}</td>
                                <td>{{ $chart['name'] }}</td>
                                <td>{{ $chart['airac'] }}</td>
                                <td>{{ isset($chart['revised_at']) ? $chart['revised_at'] : $chart['updated_at'] }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ isset($chart['link']) ? $chart['link'] : $chart['href'] }}" class="btn btn-sm btn-soft-blue"
                                            target="_blank">View</a>
                                        <button class="btn btn-sm btn-soft-danger" onclick="removeChart({{ $chart['id'] }})">Unassign</button>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ Str::upper($chart->type) }}</td>
                                <td>{{ $chart->name }}</td>
                                <td>{{ $chart->airac }}</td>
                                <td>{{ isset($chart->revised_at) ? $chart->revised_at : $chart->updated_at->format('d.m.Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ isset($chart->link) ? $chart->link : $chart->href }}" class="btn btn-sm btn-soft-blue"
                                            target="_blank">View</a>
                                        <button class="btn btn-sm btn-soft-danger" onclick="removeChart({{ $chart->id }})">Unassign</button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end" colspan="7">
                            <a href="javascript:void(0)" class="btn btn-soft-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#createChartModal"><i class="uil uil-plus me-1"></i> Assign
                                Chart</a>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="modal fade" id="createChartModal" tabindex="-1" aria-labelledby="createChartModalLabel" style="display: none;"
                aria-hidden="true" role="dialog">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-bottom p-3">
                            <h5 class="modal-title" id="createChartModalLabel">Assign Chart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-3 pt-4">
                            <form id="assignChartForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Chart</label>
                                            <select name="chart" id="chart-selector" class="form-control">
                                                @foreach (\App\Models\Navigation\Chart::all() as $chart)
                                                    <option value="{{ $chart->id }}">{{ $chart->name }} - {{ $chart->airac }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-soft-primary" id="assignChartButton">Assign Chart</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('custom-script')
    <script>
        $('#toggleChartfoxButton').on('click', () => {
            axios.patch('{{ route('administration.navigation.aerodromes.chartfox', $aerodrome->id) }}')
                .then(res => {
                    location.reload();
                });
        });

        $('#assignChartButton').on('click', () => {
            let formData = new FormData();
            formData.append('chart', $('#chart-selector').val());

            axios.post('{{ route('administration.navigation.aerodromes.chart.assign', $aerodrome->id) }}', formData)
                .then(res => {
                    if (res.data) {
                        showNoty('Chart zum Flugplatz hinzugefügt.');
                        location.reload();
                    }
                })
        });

        function removeChart(id) {

            axios.delete('{{ route('administration.navigation.aerodromes.chart.unassign', $aerodrome->id) }}', {
                data: {
                    chart: id
                }
            }).then(res => {
                if (res.data) {
                    showNoty('Chart erfolgreich entfernt.');
                    location.reload();
                }
            })
        }
    </script>
@endpush
