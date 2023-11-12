<div class="tab-pane fade bg-white p-4 rounded shadow active show" role="tabpanel" aria-labelledby="profile">
    <div class="border-bottom">
        <h5 class="text-md-start text-center">Forenaccount:</h5>
        @if($username)
            <p class="text-muted mb-4">
                Du besitzt bereits einen Forenaccount. Melde dich im Forum mit dem Benutzernamen <code>{{ $username }}</code> und deinem gewähltem Forenpasswort an.
            </p>
        @else
            <p class="text-muted mb-4">
                Du besitzt noch keinen Forenaccount. Du kannst dir hier einen Account erstellen.
            </p>
            <form wire:submit.prevent>
                <div class="mb-3">
                    <label class="form-label">neues Forenpasswort erstellen</label>
                    <div class="form-icon position-relative">
                        <i data-feather="key" class="fea icon-sm icons"></i>
                        <input wire:model="password" type="password" class="form-control ps-5" placeholder="Passwort" required="">
                    </div>
                </div>
                <div class="mb-5">
                    <div class="form-icon position-relative">
                        <button wire:loading.attr="disabled" class="btn btn-primary" wire:click="create_board_account()">Forenaccount erstellen</button>
                    </div>
                </div>
            </form>
        @endif
    </div>
    <div class="mt-4">
        <h5 class="text-md-start text-center">Teamspeak:</h5>
        <p class="text-muted mb-4">
            Du kannst bis zu 5 Teamspeak Accounts verknüpfen.
        </p>
        @if(!empty($teamspeakids))
            <table class="table">
                <thead>
                <tr>
                    <th>Teamspeak ID</th>
                    <th>Registiert</th>
                    <th>Zuletzt genutzt</th>
                    <th></th>
                </tr>
                </thead>
                @foreach($teamspeakids as $t)
                    <tr>
                        <td>{{ $t->uid }}</td>
                        <td>{{ $t->created_at }}</td>
                        <td>{{ $t->last_login }}</td>
                        <td>
                            <button wire:click="delete_teamspeak_account({{ $t->id }})" class="btn btn-soft-danger">
                                <i data-feather="trash" class="fea"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
        <form class="mt-5" wire:submit.prevent>
            <div class="mb-3">
                <label class="form-label">neue Teamspeak ID hinzufügen</label>
                <div class="form-icon position-relative">
                    <i data-feather="key" class="fea icon-sm icons"></i>
                    <input wire:model="teamspeak" type="text" class="form-control ps-5" placeholder="Teamspeak ID" required="">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-icon position-relative">
                    <button wire:loading.attr="disabled" class="btn btn-primary" wire:click="create_teamspeak_account()">Teamspeak ID verknüpfen</button>
                </div>
            </div>
        </form>

        <div class="alert bg-soft-primary fw-medium" role="alert">
            <i data-feather="info" class="fea fs-5 align-middle me-1"></i>
            Deine TS-ID findest du unter <code>
                Extras > Identitäten > Eindeutige ID (Experten-Ansicht)
            </code>.
        </div>
        <div class="alert bg-soft-warning fw-medium" role="alert">
            <i data-feather="alert-triangle" class="fea fs-5 align-middle me-1"></i>
            Stelle sicher, dass du mit dem Server verbunden bist oder schon einmal verbunden warst.
        </div>
    </div>
</div>
