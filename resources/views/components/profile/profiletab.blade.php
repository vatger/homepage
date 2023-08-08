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
            <h5 class="mb-0">Payment Methods:</h5>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addnewcard" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-plus fea icon-sm">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add</a>
        </div>

        <div class="row">
            {{--@foreach(\App\Models\Regionalgroup_remove\FlightInformationRegion::all() as $fir)
                <div class="col-md-6 mt-4 pt-2">
                    <a wire:click="firclick('XXX')">
                        <div class="card rounded shadow bg-light border-0">
                            <div class="card-body">
                                <div class="mt-4">
                                    <h5 class="text-dark">FIR {{$fir->name}}</h5>
                                    <div class="d-flex justify-content-between">
                                        <p class="h6 text-muted mb-0">subtext</p>
                                        <h6 class="mb-0 text-dark">Joined: <span class="text-muted">Date</span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div><!--end col-->
            @endforeach--}}
            <div class="col-md-6 mt-4 pt-2">
                <a href="javascript:void(0)">
                    <div class="card rounded shadow bg-dark border-0">
                        <div class="card-body">
                            <img src="assets/images/payments/payment/visaa.png" height="60" class="text-end" alt="">
                            <div class="mt-4">
                                <h5 class="text-light">•••• •••• •••• 9856</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="h6 text-muted mb-0">Calvin Carlo</p>
                                    <h6 class="mb-0 text-muted">Exp: <span class="text-muted">01/24</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!--end col-->

            <div class="col-md-6 mt-4 pt-2">
                <a href="javascript:void(0)">
                    <div class="card rounded shadow bg-info border-0">
                        <div class="card-body">
                            <img src="assets/images/payments/payment/rupay.png" height="60" class="text-end" alt="">
                            <div class="mt-4">
                                <h5 class="text-white">•••• •••• •••• 5465</h5>
                                <div class="d-flex justify-content-between">
                                    <p class="h6 text-light mb-0">Miriam Jockky</p>
                                    <h6 class="mb-0 text-light">Exp: <span class="text-light">03/23</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div><!--end col-->

            <div class="col-md-6 mt-4 pt-2">
                <div class="card rounded shadow bg-light border-0">
                    <div class="card-body">
                        <img src="assets/images/payments/payment/paypals.png" height="60" class="text-end" alt="">
                        <div class="mt-4">
                            <form>
                                <div class="mt-4 pt-3 mb-0">
                                    <div class="input-group">
                                        <input name="email" id="email" type="email" class="form-control" placeholder="Paypal Email :" required="">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary submitBnt" type="submit" id="paypalmail">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </form><!--end form-->
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div>
    </div>
</div>
