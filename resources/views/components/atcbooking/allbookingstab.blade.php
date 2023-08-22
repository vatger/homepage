<div class="card blog blog-detail border-0 shadow rounded mt-5">
    <div class="card-body content text-center">
        <h4 class="mb-2"><i class="text-primary me-1 text-center"></i><a class="text-primary">@lang('booking.atc.all.title')</a></h4>
        <div class="row justify-content-center mb-1">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <p class="text-muted para-desc mx-auto mb-0">@lang('booking.atc.all.text')</p>
                </div>
            </div>
        </div>
        <div class="w-100">
            <div class="accordion" id="general-section">
                <div class="accordion-item rounded shadow bg-white">
                    <h2 class="accordion-header">
                        <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-header-1" aria-expanded="false" aria-controls="accordion-header-1">
                            Filter
                        </button>
                    </h2>
                    <div id="accordion-header-1" class="accordion-collapse border-0 collapse" aria-labelledby="accordion-header-1"
                         data-bs-parent="#general-section" style="">
                        <div class="accordion-body text-muted">
                            <form id="filter-bookings-form">
                                <div class="row" id="search-container">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.search.from-text')</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="calendar" class="fea icon-sm icons"></i>
                                                <input name="report-start-date" id="date-start-select" type="text" class="form-control ps-5"
                                                       value="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.search.till-text')</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="calendar" class="fea icon-sm icons"></i>
                                                <input name="report-end-date" id="date-end-select" type="text" class="form-control ps-5"
                                                       value="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="search-container">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Sort by Regionalgroup</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="database" class="fea icon-sm icons"></i>
                                                <select name="report-rg" type="text" class="form-control ps-5">
                                                    <option value="-1">-</option>

                                                    @foreach (\App\Models\Navigation\Fir::all() as $fir)
                                                        <option value="{{ $fir->id }}">
                                                            {{ $fir->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Sort by Airport/Center</label>
                                            <div class="form-icon position-relative">
                                                <i data-feather="map-pin" class="fea icon-sm icons"></i>
                                                <input name="report-airport" type="text" class="form-control ps-5" placeholder="EDDF"
                                                       style="text-transform: uppercase" maxlength="4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <p class="text-muted mt-1 mb-0 pb-0 small" style="text-align: left; display: none" id="filter-count">0 results
                                matching your filter.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table class="table mb-0 table-center">
                    <thead>
                    <tr>
                        <th wire:click="sortBy('controller_id')" style="width: 33%" scope="col" class="border-bottom text-center">
                            Name
                            <i data-feather="{{ $this->getSortIconClasses('controller_id') }}"></i>
                        </th>
                        <th wire:click="sortBy('station_id')" style="width: 33%" scope="col" class="border-bottom text-center">
                            Position
                            <i data-feather="{{ $this->getSortIconClasses('station_id') }}"></i>
                        </th>
                        <th wire:click="sortBy('starts_at')" style="width: 33%" scope="col" class="border-bottom text-center">
                            Zeitraum
                            <i data-feather="{{ $this->getSortIconClasses('starts_at') }}"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($filtered_bookings as $booking)
                        <tr>
                            <td>{{ $booking->controller_id }}</td>
                            <td>{{ $booking->station->ident }}</td>
                            <td>{{ $booking->starts_at->format('h:i') }} <br> - {{ $booking->ends_at->format('h:i') }}z</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $filtered_bookings->links() }}
            </div>
        </div>
    </div>
</div>
