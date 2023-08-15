<div class="tab-pane fade bg-white p-4 rounded shadow active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <div class="pb-4 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <h5>VATGER Details:</h5>
                <div class="mt-1">
                    <x-profile.profiletabitem title="E-Mail" :text="$user->email_backup ?  : $user->email" feaicon="mail"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Vollmitglied" :text="$user->vatgerDetails->vatger_member_at ? 'YES':'NO'"
                                              :subtext="$user->vatgerDetails->vatger_member_at?->format('d.m.Y') ? : null"
                                              :feaicon="$user->vatgerDetails->vatger_member_at ? 'user-check' : 'user-x'"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="registered_at" :text="$user->vatgerDetails->registered_at->format('d.m.Y')" feaicon="calendar"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="last_seen_at" :text="$user->vatgerDetails->last_seen_at->format('d.m.Y')" feaicon="calendar"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="inactive_at" :text="$user->vatgerDetails->inactive_at?->format('d.m.Y')" feaicon="calendar"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="active_vatger_member_at" :text="$user->vatgerDetails->active_vatger_member_at->format('d.m.Y')" feaicon="calendar"></x-profile.profiletabitem>


                </div>
            </div>
            <!--end col-->

            <div class="col-md-6">
                <h5>VATSIM Details:</h5>
                <div class="mt-1">
                    <x-profile.profiletabitem title="E-Mail" :text="$user->email" feaicon="mail"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="ATC-Rating" :text="$user->vatsimDetails->rating_atc_long" :subtext="$user->vatsimDetails->rating_atc_short"
                                              feaicon="radio"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Pilot-Rating" :text="$user->vatsimDetails->rating_pilot_long" :subtext="$user->vatsimDetails->rating_pilot_short"
                                              feaicon="mic"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Mil-Rating" :text="$user->vatsimDetails->rating_military_long" :subtext="$user->vatsimDetails->rating_military_short"
                                              feaicon="target"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Region" :text="$user->vatsimDetails->region_name" :subtext="$user->vatsimDetails->region_code" feaicon="map-pin"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Division" :text="$user->vatsimDetails->division_name" :subtext="$user->vatsimDetails->division_code" feaicon="map-pin"></x-profile.profiletabitem>
                    <x-profile.profiletabitem title="Subdivision/vACC" :text="$user->vatsimDetails->subdivision_name" :subtext="$user->vatsimDetails->subdivision_code"
                                              feaicon="map-pin"></x-profile.profiletabitem>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>


    <div class="p-4">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Flight Information Region (FIR)</h5>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#change-fir-modal" hhhh="openFirSelection()">
                @if($user->fir)
                    FIR Wechseln
                @else
                    FIR Beitreten
                @endif
            </button>
        </div>

        @if($user->fir)
            <div class="row">
                <div class="col-md-6 mt-4 pt-2">
                    <div class="card rounded shadow bg-dark border-0">
                        <div class="card-body">
                            <div>
                                <h5 class="text-light">{{$user->fir?->name}}</h5>
                                <div class="d-flex justify-content-between mb-0">
                                    <p class="h6 text-muted mb-0">{{strtoupper($user->fir?->slug)}}</p>
                                    <h6 class="mb-0 text-muted">{{\Carbon\Carbon::parse($user->fir?->joined_at)->format('d.m.Y H:i')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end col-->
            </div>
        @endif
    </div>

    <div class="modal fade" id="change-fir-modal" tabindex="-1" aria-labelledby="fir-change-label" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content rounded shadow border-0">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="fir-change-label">
                        @if($user->fir)
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
                                    <option value="-1" @if(!$user->fir) disabled @endif>
                                        keine
                                        @if(!$user->fir)
                                            (Aktuell)
                                        @endif
                                    </option>
                                    @foreach(\App\Models\Groups\Fir::all() as $fir)
                                        <option value="{{$fir->id}}" @if($fir->id == $user->fir?->id) disabled @endif>
                                            {{$fir->name}}
                                            @if($fir->id == $user->fir?->fir_id)
                                                (Aktuell)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="small">
                                Du kannst die FIR alle 6 Monate wechseln. Mit diesem Wechsel bestätigst du, dass du dies verstanden hast und damit
                                einverstanden bist, bis zum {{\Carbon\Carbon::now()->add('90', 'days')->format('d.m.Y')}} keinen weiteren
                                Wechsel mehr durchführen zu können. Bestätige bitte, dass du diesen Hinweis gelesen und verstanden hast.
                            </p>
                            <input wire:model="fir_selection_checkbox" class="form-check-input" type="checkbox" value="true" id="fir-check">
                            <label for="fir-check" class="small" style="margin-left: 10px">Hinweis gelesen</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Schließen</button>
                    <button wire:click="changeFir()" type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">
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
