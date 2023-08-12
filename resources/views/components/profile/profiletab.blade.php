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
        </div>

        @if($user->fir())
        <div class="row">
            <div class="col-md-6 mt-4 pt-2">
                <a href="javascript:void(0)">
                    <div class="card rounded shadow bg-dark border-0">
                        <div class="card-body">
                            <img src="assets/images/payments/payment/visaa.png" height="60" class="text-end" alt="">
                            <div class="mt-4">
                                <h5 class="text-light">{{$user->fir->firInformation->name}}</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="h6 text-muted mb-0">Calvin Carlo</p>
                                    <h6 class="mb-0 text-muted">Exp: <span class="text-muted">01/24</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!--end col-->
        </div>
        @endif
    </div>
</div>
