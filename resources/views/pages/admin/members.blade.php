<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
            header="Mitgliederverwaltung"
            :links="[
                    route('administration.dashboard') => 'Administration',
                ]"
        />

        <x-layouts.admin.card>
            <x-layouts.admin.card-header position="left" title="Mitglieder" icon="users" :subtitle="\App\Models\Membership\User\User::count()"></x-layouts.admin.card-header>

            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class="fea icon-sm icons"></i>
                        <input class="form-control ps-5" wire:model.live="membersearch" type="search" placeholder="CID, Vorname, Nachname, E-Mail">
                    </div>
                </li>
                <li class="list-inline-item" style="width: 100%">
                    <input class="form-check-input" type="checkbox" wire:model.live="filter_ger">
                    <label class="form-check-label" for="">nur GER zugeordnete anzeigen</label>
                </li>
                <li class="list-inline-item" style="width: 100%">
                    <input class="form-check-input" type="checkbox" wire:model.live="filter_active">
                    <label class="form-check-label" for="">active_member anzeigen</label>
                </li>
                <li class="list-inline-item" style="width: 100%">
                    <input class="form-check-input" type="checkbox" wire:model.live="filter_inactive">
                    <label class="form-check-label" for="">inactive_member anzeigen</label>
                </li>
            </x-layouts.admin.card-header>


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
                        {{--  <th class="border-bottom p-3">E-Mail</th> --}}
                        <th class="border-bottom p-3">(Sub)division</th>
                        <th class="border-bottom p-3">FIR</th>
                        <th class="border-bottom p-3">Rating</th>
                        <th class="border-bottom p-3">Beitritt</th>
                        <th class="border-bottom p-3">Status</th>
                        <th class="border-bottom p-3"></th>
                    </tr>

                    </thead>
                    <tbody id="member-list-content">
                    @foreach ($filtered_members as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->username }}</td>
                            {{--<td>{{ $member->email }}</td>--}}
                            <td>
                                {{ $member->vatsimDetails->region_code }}
                                / {{ $member->vatsimDetails->division_code }}
                                {{ $member->vatsimDetails->subdivision_code ? '/ ' . $member->vatsimDetails->subdivision_code : '' }}
                            </td>
                            <td>{{ $member->fir?->slug ?? '-'}} <small>{{ $member->fir ? '(' . \Carbon\Carbon::parse($member->fir->joined_at)->format('d.m.Y') . ')': '' }}</small></td>
                            <td>{{ $member->vatsimDetails->rating_atc_short }} / {{ $member->vatsimDetails->rating_pilot_short }}
                                / {{ $member->vatsimDetails->rating_military_short }}</td>
                            <td>{{ $member->vatgerDetails->registered_at->format('d.m.Y') }}</td>
                            <td>
                                <small>
                                    {{ $member->vatgerDetails->is_inactive ? 'vatger_inactive' : '' }}
                                    {{ $member->vatgerDetails->is_vatger_member ? 'vatger_member' : '' }}
                                    {{ $member->vatgerDetails->is_vatger_voter ? 'vatger_voter' : '' }}
                                </small>
                            </td>
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


        </x-layouts.admin.card>
    </div>
</div>
