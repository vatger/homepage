<div>
    <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
             style="background: url('{{ iasset('images/profile/profile_1.png') }}') center center; background-size: cover">
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%);"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--ed container-->
    </section>

    <section class="section pt-0" style="margin-top: -55px">
        <div class="container mt-lg-3">
            <div class="card public-profile border-0 rounded shadow mb-3 form-control" style="z-index: 1;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-9">
                            <div class="row align-items-end">
                                <div class="col-md-7 text-md-start text-center mt-4 mt-sm-0">
                                    <h3 class="title text-dark mb-0">@yield('section-title', $user->username)</h3>
                                    <small class="text-muted h6 me-2">@yield('section-subtitle', $user->id)</small>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-log-auto col-lg-4 col-12 mb-4">
                    <div class="sidebar sticky-bar p-4 rounded shadow">


                        <div class="widget">
                            <ul class="nav nav-pills nav-justified flex-column bg-white p-3 mb-0" role="tablist">
                                <li class="nav-item mt-2 pb-2" wire:click="sel('profile')">
                                    <a class="nav-link rounded {{ $tab == 'profile' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">@lang('profile.profile.menu.profile-text') / FIR</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item mt-2 pb-2" wire:click="sel('notifications')">
                                    <a class="nav-link rounded {{ $tab == 'notifications' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">Notifications</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item mt-2 pb-2" wire:click="sel('settings')">
                                    <a class="nav-link rounded {{ $tab == 'settings' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">Settings</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item mt-2 pb-2" wire:click="sel('accounts')">
                                    <a class="nav-link rounded {{ $tab == 'accounts' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">Teamspeak/Forum</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item mt-2 pb-2" wire:click="sel('surveykeys')">
                                    <a class="nav-link rounded {{ $tab == 'surveykeys' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0">Survey Keys</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                @if($user->staffDetails)
                                    <li class="nav-item mt-2 pb-2" wire:click="sel('staff')">
                                        <a class="nav-link rounded {{ $tab == 'staff' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                           aria-controls="profile" aria-selected="true">
                                            <div class="text-start py-1 px-2">
                                                <h6 class="mb-0">Staff</h6>
                                            </div>
                                        </a>
                                        <!--end nav link-->
                                    </li>
                                    <!--end nav item-->
                                @endif
                                <li class="nav-item mt-2 pt-2 border-top">
                                    <a href="{{ route('vatsim.authentication.connect.logout') }}" class="nav-link rounded" aria-selected="false">
                                        <div class="text-start py-1 px-2">
                                            <h6 class="mb-0 text-danger">@lang('navigation.user.logout')</h6>
                                        </div>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-8 col-12 tab-content">
                    <div class="tab-content" id="pills-tabContent">
                        @switch($tab)
                            @case('notifications')
                                <livewire:profile.notification-tab />
                                @break
                            @case('settings')
                                <livewire:profile.settings-tab />
                                @break
                            @case('accounts')
                                @if (\Illuminate\Support\Facades\Auth::user()->vatsimDetails->rating_pilot == -1)
                                    <div class="tab-pane fade bg-white p-4 rounded shadow active show" role="tabpanel" aria-labelledby="profile">
                                        <h5>Fehler</h5>
                                        <div class="alert alert-danger mt-4">
                                            @lang('profile.profile.error.account-inactive-text')
                                        </div>

                                        <div class="small text-muted mt-4">@lang('profile.profile.error.contact-support-text')</div>
                                    </div>
                                @else
                                    <livewire:profile.accounts-tab />
                                @endif
                                @break
                            @case('surveykeys')
                                <x-profile.surveykeys></x-profile.surveykeys>
                                @break
                            @case('staff')
                                <x-profile.stafftab></x-profile.stafftab>
                                @break
                            @default
                                <livewire:profile.profile-tab />
                        @endswitch
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
