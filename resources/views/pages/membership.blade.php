<div>
    <section class="profile-hero bg-half-170 d-table w-100" id="hero-section"
             style="background: url('{{ iasset('images/profile/profile_1.png') }}') center center; background-size: cover">
        <div class="bg-overlay profile-hero-overlay"></div>
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

    <section class="section profile-page pt-0">
        <div class="container mt-lg-3">
            <div class="card profile-summary border-0 mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-avatar">
                                    <i data-feather="user" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <span class="profile-eyebrow">@lang('profile.profile.menu.profile-text')</span>
                                    <h2 class="title text-dark mb-1">@yield('section-title', $user->username)</h2>
                                    <span class="text-muted">VATSIM ID @yield('section-subtitle', $user->id)</span>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
            </div>
            <div class="row mt-4 g-4">
                <div class="col-lg-3 col-12">
                    <div class="sidebar profile-sidebar sticky-bar">
                        <div class="widget">
                            <ul class="nav flex-column mb-0 profile-navigation" role="tablist">
                                <li class="nav-item" wire:click="sel('profile')">
                                    <a class="nav-link profile-nav-link {{ $tab == 'profile' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <i data-feather="user" aria-hidden="true"></i>
                                        <span>@lang('profile.profile.menu.profile-text') / FIR</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item" wire:click="sel('notifications')">
                                    <a class="nav-link profile-nav-link {{ $tab == 'notifications' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <i data-feather="bell" aria-hidden="true"></i>
                                        <span>Notifications</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item" wire:click="sel('settings')">
                                    <a class="nav-link profile-nav-link {{ $tab == 'settings' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <i data-feather="settings" aria-hidden="true"></i>
                                        <span>Settings/Accounts</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item" wire:click="sel('accounts')">
                                    <a class="nav-link profile-nav-link {{ $tab == 'accounts' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <i data-feather="headphones" aria-hidden="true"></i>
                                        <span>Teamspeak</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                                <li class="nav-item" wire:click="sel('surveykeys')">
                                    <a class="nav-link profile-nav-link {{ $tab == 'surveykeys' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                       aria-controls="profile" aria-selected="true">
                                        <i data-feather="clipboard" aria-hidden="true"></i>
                                        <span>Survey/Umfragen</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                @if($user->staffDetails)
                                    <li class="nav-item" wire:click="sel('staff')">
                                        <a class="nav-link profile-nav-link {{ $tab == 'staff' ? 'active' : '' }}" data-bs-toggle="pill" role="tab"
                                           aria-controls="profile" aria-selected="true">
                                            <i data-feather="shield" aria-hidden="true"></i>
                                            <span>Staff</span>
                                        </a>
                                        <!--end nav link-->
                                    </li>
                                    <!--end nav item-->
                                @endif
                                <li class="nav-item profile-nav-logout">
                                    <a href="{{ route('vatsim.authentication.connect.logout') }}" class="nav-link profile-nav-link text-danger" aria-selected="false">
                                        <i data-feather="log-out" aria-hidden="true"></i>
                                        <span>@lang('navigation.user.logout')</span>
                                    </a>
                                    <!--end nav link-->
                                </li>
                                <!--end nav item-->
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end col-->

                <div class="col-lg-9 col-12 tab-content profile-workspace">
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
