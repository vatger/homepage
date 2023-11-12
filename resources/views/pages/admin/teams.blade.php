<div class="container-fluid">
    <div class="layout-specing">
        <x-layouts.admin.content
                header="Gruppenverwaltung"
        ></x-layouts.admin.content>

        <x-layouts.admin.card>
            <x-layouts.admin.card-header position="left" title="Teams" :subtitle="App\Models\Groups\Team::count()">
                @if($limited_selection)
                    <p class="text-warning">Es werden nur Teams angezeit, die dein Team verwalten kann.</p>
                @endif
            </x-layouts.admin.card-header>
            <x-layouts.admin.card-header position="right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="form-icon position-relative">
                        <i data-feather="search" class="fea icon-sm icons"></i>
                        <input wire:model.live="search" class="form-control ps-5" type="text" placeholder="Teamname">
                    </div>
                </li>
            </x-layouts.admin.card-header>

            <div class="row p-4 table-responsive">
                <table class="table table-center bg-white mb-0">
                    <thead>
                    <tr class="text-center">
                        <th class="border-bottom p-3">Name</th>
                        <th class="border-bottom p-3">Super Team</th>
                        <th class="border-bottom p-3">Aktion</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $team)
                        <tr class="text-center">
                            <td>{{ $team->name }}</td>
                            <td>{{ $team->super_team?->name }}</td>
                            <td>
                                <a href="{{ route('administration.team', ['team' => $team]) }}" class="btn btn-sm btn-soft-primary p-1 px-3" style="font-size: 15px">
                                    <i data-feather="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @can('membership.teams.edit')
                        <tr></tr>
                        <tr class="text-center">
                            <td>
                                <label>
                                    <input wire:model="new_name" class="form-control">
                                </label>
                            </td>
                            <td></td>
                            <td>
                                <button wire:click="create_team" class="btn btn-soft-success">
                                    <i data-feather="plus" class="fea icon-sm"></i>
                                </button>
                            </td>
                        </tr>
                    @endcan
                    </tbody>
                </table>
                {{ $teams->links() }}
            </div>
        </x-layouts.admin.card>
    </div>
</div>
