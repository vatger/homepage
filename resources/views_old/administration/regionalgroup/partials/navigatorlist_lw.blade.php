<div class="row px-4 table-responsive">
    <div id="content-container">
        <div class="row p-4 table-responsive">
            <table class="table table-center bg-white mb-0">
                <thead>
                    <tr class="text">
                        <th class="border-bottom p-3">CID</th>
                        <th class="border-bottom p-3">Name</th>
                        <th class="border-bottom p-3">Rolle</th>
                        <th class="border-bottom p-3">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->firstname }} {{ $member->lastname }}</td>
                            <td>{{ $member->pivot->chief ? 'Leitender Mentor ' : '' }} {{ $member->pivot->deputy ? 'Senior Mentor' : '' }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-sm {{ $member->pivot->chief ? 'btn-soft-danger' : 'btn-soft-success' }}"
                                        wire:click="toggle_chief({{ $member->id }})">Chief</button>
                                    <button class="btn btn-sm {{ $member->pivot->deputy ? 'btn-soft-danger' : 'btn-soft-success' }}"
                                        wire:click="toggle_deputy({{ $member->id }})">Deputy</button>
                                    <button class="btn btn-sm btn-soft-danger" wire:click="kick({{ $member->id }})">Remove</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $members->links() }}
        </div>
    </div>

    <div class="row px-4 table-responsive">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12" style="text-align: left"></div>
            <div class="col-lg-6 col-md-6 col-sm-12" style="text-align: right">
                <li class="list-inline-item" style="width: 100%">
                    <div class="">Navigator hinzufügen:</div>
                    <input class="form-control mt-2" wire:model="membersearch" type="search" placeholder="CID">
                    <button class="btn btn-soft-primary mt-2" wire:click="add()">Add</button>
                </li>
            </div>
        </div>
    </div>
</div>
