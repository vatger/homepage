<div>
    <div class="row px-4 table-responsive">
        <div class="row border-bottom pb-3">
            <div class="col-lg-6 col-md-6 col-sm-12 mt-2" style="text-align: right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search fea icon-sm icons">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input class="form-control ps-5" wire:model="membersearch" type="search" placeholder="CID, Vorname, Nachname, EMail">
                    </div>
                </li>
            </div>
        </div>

        <div id="content-container">
            <div class="row p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                        <tr class="text">
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('id')">CID <i @class($this->getSortIconClasses('id')) />
                            </th>
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('lastname')">Name <i
                                    @class($this->getSortIconClasses('lastname')) /></th>
                            <th class="border-bottom p-3" style="white-space: nowrap">Rating</th>
                            <th class="border-bottom p-3" style="white-space: nowrap" wire:click="sortBy('pivot_created_at')">Beitritt <i
                                    @class($this->getSortIconClasses('pivot_created_at')) /></th>
                            <th class="border-bottom p-3" style="white-space: nowrap">Aktion</th>
                        </tr>
                    </thead>
                    <tbody id="member-list-content">
                        @foreach ($filtered_members as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>{{ $member->firstname }} {{ $member->lastname }}</td>
                                <td>{{ $member->userData->atcRatingShort }} / {{ $member->userData->pilotRatingShort }}</td>
                                <td>{{ $member->pivot->created_at->format('d.m.Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm btn-soft-info" wire:click="view_member({{ $member->id }})">Info</button>
                                        <button class="btn btn-sm btn-soft-danger" wire:click="delete_member({{ $member->id }})">Remove</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $filtered_members->links() }}
            </div>
        </div>

    </div>

    <div id="del_modal" class="modal fade" tabindex="-1" aria-modal="true" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-body py-5">
                    <div class="text-center">
                        <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
                            style="height: 95px; width:95px;">
                            <h1 class="mb-0"><i class="mdi mdi-alert-circle-outline"></i></h1>
                        </div>
                        <div>
                            @if (!empty($delete_account_id))
                                <h4 class="mt-4">Achtung, nochmal prüfen</h4>
                                <div class="mt-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Name :</h6>
                                            <p class="text-muted">{{ $delete_account?->username }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Vatsim-ID :</h6>
                                            <p class="text-muted">{{ $delete_account?->id }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">RG-Beitritt :</h6>
                                            <p class="text-muted">{{ $delete_account?->pivot->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group mt-2">
                                    <button class="btn btn-soft-info" wire:click="delete_member(null)">Nix tun</button>
                                    <button class="btn btn-soft-danger" wire:click="delete_member({{ $delete_account?->id }}, true)">Mitglied
                                        rauswerfen</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="view_modal" class="modal fade" tabindex="-1" aria-modal="true" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-body py-5">
                    <div class="text-center">
                        <div class="icon d-flex align-items-center justify-content-center bg-soft-info rounded-circle mx-auto"
                            style="height: 95px; width:95px;">
                            <h1 class="mb-0"><i class="mdi mdi-information-outline"></i></h1>
                        </div>
                        <div>
                            @if (!empty($details_account_id))
                                <h4 class="mt-4">Mitglied, {{ $details_account?->username }}</h4>
                                <div class="mt-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Email :</h6>
                                            <p class="text-muted">{{ $details_account?->email }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Ratings :</h6>
                                            <p class="text-muted">ATC: {{ $details_account?->userData->atcRatingShort }} | Pilot:
                                                {{ $details_account?->userData->pilot_rating_short }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Zuordnung :</h6>
                                            <p class="text-muted">{{ $details_account?->userData->region_code }} |
                                                {{ $details_account?->userData->division_code }} |
                                                {{ $details_account?->userData->subdivision_code }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">Vatsim-ID :</h6>
                                            <p class="text-muted">{{ $details_account?->id }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-1">
                                            <h6 class="text-primary mb-0">RG-Beitritt :</h6>
                                            <p class="text-muted"> {{ $details_account->pivot->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group mt-2">
                                    <button class="btn btn-soft-info" wire:click="view_member(null)">Schließen</button>
                                    <a class="btn btn-soft-info"
                                        href="{{ route('administration.membership.user.view', ['user' => $details_account_id]) }}">Profil</a>
                                    <button class="btn btn-soft-danger" wire:click="delete_member({{ $details_account_id }})">Rauswerfen</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-script')
    <script>
        function makeChief(id) {
            axios.post('{{ route('administration.regionalgroup.staff.chief', ['regionalgroup' => $regionalgroup]) }}', {
                id: id
            }).then(res => {
                if (res.data) {
                    new Noty({
                        text: 'New Chief set',
                        progressBar: true,
                        timeout: 5000,
                        layout: 'topRight',
                        type: 'success',
                    }).on('afterClose', function() {
                        location.reload();
                    }).show();
                }
            });
        }

        function makeDeputy(id) {
            axios.post('{{ route('administration.regionalgroup.staff.deputy', ['regionalgroup' => $regionalgroup]) }}', {
                id: id
            }).then(res => {
                if (res.data) {
                    new Noty({
                        text: 'New Chief set',
                        progressBar: true,
                        timeout: 5000,
                        layout: 'topRight',
                        type: 'success',
                    }).on('afterClose', function() {
                        location.reload();
                    }).show();
                }
            });
        }
    </script>
@endpush
