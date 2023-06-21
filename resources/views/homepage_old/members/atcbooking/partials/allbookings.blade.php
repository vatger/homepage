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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-calendar fea icon-sm icons">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                                    </rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                                <input name="report-start-date" id="date-start-select" type="text" class="form-control ps-5"
                                                    value="{{ \Carbon\Carbon::now()->format('d.m.Y') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">@lang('booking.atc.search.till-text')</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-calendar fea icon-sm icons">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                                    </rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
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
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-database fea icon-sm icons">
                                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                                </svg> <select name="report-rg" type="text" class="form-control ps-5">
                                                    <option value="-1">-</option>
                                                    @foreach (\App\Models\Regionalgroup\Regionalgroup::all() as $rg)
                                                        <option value="{{ $rg->id }}" @if (Auth::user()->isMemberOfRegionalGroup($rg)) selected @endif>
                                                            {{ $rg->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label class="form-label">Sort by Airport</label>
                                            <div class="form-icon position-relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="feather feather-map-pin fea icon-sm icons">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg> <input name="report-airport" type="text" class="form-control ps-5" placeholder="EDDF"
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
                            <th style="width: 33%" scope="col" class="border-bottom text-center">Name</th>
                            <th style="width: 33%" scope="col" class="border-bottom text-center">Position</th>
                            <th style="width: 33%" scope="col" class="border-bottom text-center">Zeitraum</th>
                        </tr>
                    </thead>
                    <tbody id="atcBookings">
                        <tr>
                            <td colspan="4">No Positions have been booked in this timeframe.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
