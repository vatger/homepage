<div class="tab-pane profile-panel fade bg-white active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <div class="p-4 p-lg-5 border-bottom">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="profile-details-group">
                    <span class="profile-eyebrow">Membership</span>
                    <h4 class="mt-2 mb-4">VATGER Details</h4>
                    <x-profile.profiletabitem title="E-Mail (Forum)" :text="$user->email_backup ?? 'N/A' " feaicon="mail">
                        @if($user->email_backup)
                            <button wire:click="changeEmail()" class="btn btn-sm btn-primary">reset to VATSIM E-Mail</button>
                        @endif
                    </x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Vollmitglied" :text="$user->vatgerDetails->is_vatger_member ? 'YES':'NO'"
                                              :subtext="$user->vatgerDetails->vatger_member_at?->format('d.m.Y') ? : null"
                                              :feaicon="$user->vatgerDetails->is_vatger_member ? 'user-check' : 'user-x'"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Wahlberechtigt" :text="$user->vatgerDetails->is_vatger_voter ? 'YES':'NO'"
                                              :subtext="$user->vatgerDetails->active_vatger_member_at?->format('d.m.Y') ? : 'inactive'"
                                              :feaicon="$user->vatgerDetails->is_vatger_voter ? 'user-check' : 'user-x'"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Wahlberechtigt (FIR)" :text="$user->vatgerDetails->is_fir_voter ? 'YES':'NO'"
                                              :subtext="$user->fir_membership?->active_fir_member_at?->format('d.m.Y') ? : 'inactive'"
                                              :feaicon="$user->vatgerDetails->is_fir_voter ? 'user-check' : 'user-x'"></x-profile.profiletabitem>


                </div>
            </div>
            <!--end col-->

            <div class="col-md-6">
                <div class="profile-details-group">
                    <span class="profile-eyebrow">Network</span>
                    <h4 class="mt-2 mb-4">VATSIM Details</h4>
                    <x-profile.profiletabitem title="E-Mail" :text="$user->email" feaicon="mail">
                        <a href="https://my.vatsim.net/user/email">change</a>
                    </x-profile.profiletabitem>
                    <x-profile.profiletabitem title="ATC-Rating" :text="$user->vatsimDetails->rating_atc_long" :subtext="$user->vatsimDetails->rating_atc_short"
                                              feaicon="radio"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Pilot-Rating" :text="$user->vatsimDetails->rating_pilot_long" :subtext="$user->vatsimDetails->rating_pilot_short"
                                              feaicon="mic"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Mil-Rating" :text="$user->vatsimDetails->rating_military_long" :subtext="$user->vatsimDetails->rating_military_short"
                                              feaicon="target"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Region" :text="$user->vatsimDetails->region_name" :subtext="$user->vatsimDetails->region_code" feaicon="map-pin">
                        <a href="https://my.vatsim.net/user/region">change</a>
                    </x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Division" :text="$user->vatsimDetails->division_name" :subtext="$user->vatsimDetails->division_code" feaicon="map-pin">
                        <a href="https://my.vatsim.net/user/region">change</a>
                    </x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Subdivision/vACC" :text="$user->vatsimDetails->subdivision_name" :subtext="$user->vatsimDetails->subdivision_code"
                                              feaicon="map-pin">
                        @if(strtolower($user->vatsimDetails->division_code) == "eud")
                            <a href="https://members.vateud.net">change</a>
                        @endif
                    </x-profile.profiletabitem>
                    <a href="{{ route('member.refresh') }}" class="btn btn-sm btn-primary mt-3">Fetch new VATSIM Data</a>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>


    <div class="p-4 p-lg-5 profile-fir-section">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Flight Information Region (FIR)</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#change-fir-modal" @if(!$user->vatgerDetails->can_change_fir) disabled @endif>
                @if($user->fir)
                    FIR Wechseln
                @else
                    FIR Beitreten
                @endif
            </button>
        </div>
        @if(!$user->vatgerDetails->can_change_fir)
            <div class="row mt-4">
                <div class="alert bg-soft-primary fw-medium" role="alert">
                    <i data-feather="info" class="fea fs-5 align-middle me-1"></i>
                    {{ $user->vatgerDetails->can_change_fir_reason }}
                </div>
            </div>
        @endif
        @if($user->fir)
            <div class="row">
                <div class="col-md-6 mt-4 pt-2">
                            <div class="card profile-fir-card is-current border-0">
                        <div class="card-body">
                            <div>
                                <h5 class="text-light">{{$user->fir?->name}}</h5>
                                <div class="d-flex justify-content-between mb-0">
                                    <p class="h6 text-light mb-0">{{strtoupper($user->fir?->slug)}}</p>
                                    <h6 class="mb-0 text-light"> joined {{ $user->fir_membership?->joined_at->format('d.m.Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
                @foreach(\App\Models\Groups\Fir::all() as $f)
                    @if($f->id != $user->fir?->fir_id)
                        <div class="col-md-6 mt-4 pt-2">
                            <div class="card profile-fir-card border-0">
                                <div class="card-body">
                                    <div>
                                        <h5 class="text-light">{{$f->name}}</h5>
                                        <div class="d-flex justify-content-between mb-0">
                                            <p class="h6 text-muted mb-0">{{strtoupper($f->slug)}}</p>
                                            <h6 class="mb-0 text-muted">{{ '' }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    @endif
                @endforeach
            </div>
        @endif

    </div>

    <div class="modal fade profile-fir-modal" id="change-fir-modal" tabindex="-1" aria-labelledby="fir-change-label" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="fir-change-label">
                        @if($userfir)
                            FIR Wechseln
                        @else
                            FIR Beitreten
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label" for="fir-select">FIR Auswählen</label>
                                <select wire:model="fir_selection" class="form-select form-control" id="fir-select">
                                    <option value="-1" @if(!$userfir) disabled @endif>
                                        keine
                                        @if(!$userfir)
                                            (Aktuell)
                                        @endif
                                    </option>
                                    @foreach(\App\Models\Groups\Fir::all() as $fir)
                                        <option value="{{$fir->id}}" @if($fir->id == $userfir?->fir_id) disabled @endif>
                                            {{$fir->name}}
                                            @if($fir->id == $userfir?->fir_id)
                                                (Aktuell)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="small">
                                Du kannst die FIR alle 90 Tage wechseln. Mit diesem Wechsel bestätigst du, dass du dies verstanden hast und damit
                                einverstanden bist, bis zum {{\Carbon\Carbon::now()->addDays(90)->format('d.m.Y')}} keinen weiteren
                                Wechsel mehr durchführen zu können. Bestätige bitte, dass du diesen Hinweis gelesen und verstanden hast.
                            </p>
                            <input wire:model="fir_selection_checkbox" class="form-check-input" type="checkbox" value="true" id="fir-check">
                            <label for="fir-check" class="small" style="margin-left: 10px">Hinweis gelesen</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button wire:click="changeFir()" wire:loading.attr="disabled" wire:target="changeFir"
                            type="button" class="btn btn-sm btn-primary">
                        @if($user->fir)
                            FIR Wechseln
                        @else
                            FIR Beitreten
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
