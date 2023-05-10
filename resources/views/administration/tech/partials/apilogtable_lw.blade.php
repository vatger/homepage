@php
    use App\OpenApi\Models\ApiLog;
    use Carbon\Carbon;
@endphp
<div>
    <div class="card shadow border-0">
        <div class="row row-container p-4 border-bottom">
            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                    <div class="d-flex align-items-center">
                        <div class="icon text-center rounded-pill">
                            <i class="mdi mdi-folder-text fs-4 mb-0"></i>
                        </div>
                        <div class="flex-1 ms-3">
                            <h6 class="mb-0 text-muted">Systemlogs</h6>
                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ ApiLog::query()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-2" style="text-align: right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search fea icon-sm icons">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input name="search_string" id="syslog-search-input" class="form-control ps-5" type="text"
                            placeholder="{{ Carbon::now()->format('d.m.Y') }}">
                    </div>
                </li>
            </div>
        </div>

        <div id="content-container">
            <div class="row row-container p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                        <tr class="text-center">
                            <th class="border-bottom p-3 w-25">Typ</th>
                            <th class="border-bottom p-3 w-25">Pfad</th>
                            <th class="border-bottom p-3 w-25">Datum</th>
                        </tr>

                    </thead>

                </table>
                <table class="table table-center bg-white mb-0">
                    <thead>
                        <tr class="text">
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">CID <i @class($this->getSortIconClasses('id')) />
                            </th>
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">Name <i
                                    @class($this->getSortIconClasses('id')) /></th>
                            <th class="border-bottom p-3" style="white-space: nowrap">Rating</th>
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">Beitritt <i
                                    @class($this->getSortIconClasses('id')) /></th>
                            <th class="border-bottom p-3" style="white-space: nowrap">Aktion</th>
                        </tr>
                    </thead>
                    <tbody id="member-list-content">
                        @foreach ($filtered_logs as $log)
                            <tr>
                                <td>{{ $log->time }}</td>
                                <td>{{ $log->token_id }}</td>
                                <td>{{ $log->endpoint }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm btn-soft-info" wire:click="view_log({{ $log->id }})">Info</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $filtered_logs->links() }}
            </div>
        </div>
    </div>
</div>
