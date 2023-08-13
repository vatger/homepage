<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Mitgliederverwaltung</h5>

            <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">Mitgliederverwaltung</li>
                </ul>
            </nav>
        </div>

        <div class="row">
            <div class="col mt-4">
                <div class="card shadow border-0">
                    <div>
                        <div class="row p-4 border-bottom">
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-1">
                                <div class="features feature-primary d-flex justify-content-between align-items-center bg-white">
                                    <div class="d-flex align-items-center">
                                        <div class="icon text-center rounded-pill">
                                            <i class="mdi mdi-account-group fs-4 mb-0"></i>
                                        </div>
                                        <div class="flex-1 ms-3">
                                            <h6 class="mb-0 text-muted">Mitglieder</h6>
                                            <p class="fs-5 text-dark fw-bold mb-0" id="element-count">{{ \App\Models\Membership\User\User::count() }}</p>
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
                                        <input class="form-control ps-5" wire:model.live="membersearch" type="search" placeholder="CID, Vorname, Nachname, EMail">
                                    </div>
                                </li>
                                <div class="form-check form-check-inline">
                                    <div class="mb-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.live="filter_ger">
                                            <label class="form-check-label" for="flexCheckDefault1">show 'EUD/GER' only</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="content-container">
                            <div class="row p-4 table-responsive">
                                <table class="table table-center bg-white mb-0">
                                    <thead>
                                    <tr class="text">
                                        <th class="border-bottom p-3" wire:click="sortBy('id')">CID
                                            <i data-feather="{{ $this->getSortIconClasses('id') }}"></i>
                                        </th>
                                        <th class="border-bottom p-3" wire:click="sortBy('lastname')">Name
                                            <i data-feather="{{ $this->getSortIconClasses('lastname') }}"></i>
                                        </th>
                                        <th class="border-bottom p-3">E-Mail</th>
                                        <th class="border-bottom p-3">(Sub)division</th>
                                        <th class="border-bottom p-3">Rating</th>
                                        <th class="border-bottom p-3" wire:click="sortBy('created_at')">Beitritt
                                            <i data-feather="{{ $this->getSortIconClasses('created_at') }}"></i>
                                        </th>
                                        <th class="border-bottom p-3"></th>
                                    </tr>

                                    </thead>
                                    <tbody id="member-list-content">
                                    @foreach ($filtered_members as $member)
                                        <tr>
                                            <td>{{ $member->id }}</td>
                                            <td>{{ $member->username }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                @php
                                                    //dd($member->vatsimDetails);
                                                @endphp
                                                {{ $member->vatsimDetails->region_code }}
                                                /{{ $member->vatsimDetails->division_code }}{{ $member->vatsimDetails->subdivision_code ? '/' . $member->vatsimDetails->subdivision_code : '' }}
                                            </td>
                                            <td>{{ $member->vatsimDetails->rating_atc_short }} / {{ $member->vatsimDetails->rating_pilot_short }}
                                                / {{ $member->vatsimDetails->rating_military_short }}</td>
                                            <td>{{ $member->created_at->format('d.m.Y') }}</td>
                                            <td>

                                                <a href="{{ route('administration.member', ['user' => $member->id]) }}">
                                                    <button class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px">
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                {{ $filtered_members->links() }}
                            </div>
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
