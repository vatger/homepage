<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gruppenverwaltung</h5>

            <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">Gruppenverwaltung</li>
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
                                        <i class="mdi mdi-account-group fs-4 mb-0"></i>
                                    </div>
                                    <div class="flex-1 ms-3">
                                        <h6 class="mb-0 text-muted">Teams</h6>
                                        <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{  App\Models\Groups\Team::count() }}</p>
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
                                    <input wire:model.live="search" class="form-control ps-5" type="text" placeholder="Teamname">
                                </div>
                            </li>
                        </div>
                    </div>

                    <div id="content-container">
                        <div class="row p-4 table-responsive">
                            <table class="table table-center bg-white mb-0">
                                <thead>
                                <tr class="text-center">
                                    <th class="border-bottom p-3">ID</th>
                                    <th class="border-bottom p-3">Name</th>
                                    <th class="border-bottom p-3">Aktion</th>
                                </tr>
                                </thead>
                                <tbody id="group-list-content">
                                @foreach($teams as $team)
                                    <tr class="text-center">
                                        <td>{{ $team->id }}</td>
                                        <td>{{ $team->name }}</td>
                                        <td>{{ $team->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <p class="text-muted mt-2" id="dataset-length"></p>


                            {{ $teams->links() }}

                            {{--
                            <ul class="pagination mb-0 mt-4" style="display: none">
                                <li class="page-item" data-action="prev"><a class="page-link" id="page-item-prev" href="javascript:void(0)">Zurück</a>
                                </li>
                                <li class="page-item" id="page-item-first" data-action="first"><a class="page-link" href="javascript:void(0)">1</a>
                                </li>
                                <li class="page-item active"><a class="page-link" id="current-page-indicator"
                                                                href="javascript:void(0)">{{ $groups->currentPage() }}</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0)" id="page-indicator-dots">...</a></li>
                                <li class="page-item" id="page-item-last" data-action="last"><a class="page-link"
                                                                                                href="javascript:void(0)">{{ $groups->lastPage() }}</a></li>
                                <li class="page-item" data-action="next"><a class="page-link" id="page-item-next"
                                                                            href="javascript:void(0)">Nächste</a></li>
                            </ul>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
    </div>
    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
</div>
