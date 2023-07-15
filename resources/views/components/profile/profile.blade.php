@props(['user'])

<div class="tab-pane fade bg-white p-4 rounded shadow active show" id="profile-tab" role="tabpanel" aria-labelledby="profile">
    <div class="pb-4 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <h5>VATGER Details:</h5>
                <div class="mt-1">
                    <x-profile.profileitem title="E-Mail" :text="$user->email_backup ?  : $user->email" feaicon="mail"></x-profile.profileitem>
                    <x-profile.profileitem title="Vollmitglied" :text="$user->vatgerDetails->vatger_member_at ? 'YES':'NO'" :subtext="$user->vatgerDetails->vatger_member_at?->format('d.m.Y') ? : null"
                                           :feaicon="$user->vatgerDetails->vatger_member_at ? 'user-check' : 'user-x'"></x-profile.profileitem>
                    <x-profile.profileitem title="registered_at" :text="$user->vatgerDetails->registered_at->format('d.m.Y')" feaicon="calendar"></x-profile.profileitem>
                    <x-profile.profileitem title="last_seen_at" :text="$user->vatgerDetails->last_seen_at->format('d.m.Y')" feaicon="calendar"></x-profile.profileitem>
                    <x-profile.profileitem title="inactive_at" :text="$user->vatgerDetails->inactive_at?->format('d.m.Y')" feaicon="calendar"></x-profile.profileitem>

                </div>
            </div>
            <!--end col-->

            <div class="col-md-6">
                <h5>VATSIM Details:</h5>
                <div class="mt-1">
                    <x-profile.profileitem title="E-Mail" :text="$user->email" feaicon="mail"></x-profile.profileitem>
                    <x-profile.profileitem title="ATC-Rating" :text="$user->vatsimDetails->rating_atc_long" :subtext="$user->vatsimDetails->rating_atc_short" feaicon="radio"></x-profile.profileitem>
                    <x-profile.profileitem title="Pilot-Rating" :text="$user->vatsimDetails->rating_pilot_long" :subtext="$user->vatsimDetails->rating_pilot_short"
                                           feaicon="mic"></x-profile.profileitem>
                    <x-profile.profileitem title="Mil-Rating" :text="$user->vatsimDetails->rating_military_long" :subtext="$user->vatsimDetails->rating_military_short"
                                           feaicon="target"></x-profile.profileitem>
                    <x-profile.profileitem title="Region" :text="$user->vatsimDetails->region_name" :subtext="$user->vatsimDetails->region_code" feaicon="map-pin"></x-profile.profileitem>
                    <x-profile.profileitem title="Division" :text="$user->vatsimDetails->division_name" :subtext="$user->vatsimDetails->division_code" feaicon="map-pin"></x-profile.profileitem>
                    <x-profile.profileitem title="Subdivision/vACC" :text="$user->vatsimDetails->subdivision_name" :subtext="$user->vatsimDetails->subdivision_code"
                                           feaicon="map-pin"></x-profile.profileitem>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div>
