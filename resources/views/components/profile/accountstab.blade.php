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
            <div class="mb-3">
                <label class="form-label">Forenpasswort</label>
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
        @endif
    </div>
    <div class="mt-4">
        <h5 class="text-md-start text-center">Teamspeak:</h5>

    </div>
</div>
