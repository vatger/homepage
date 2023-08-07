<div>
    <section class="bg-half-170 bg-primary d-table w-100" id="hero-section"
             style="background: url('{{ asset('images/profile/profile_1.png') }}') center center; background-size: cover">
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
            <div class="card public-profile border-0 rounded shadow mb-3" style="z-index: 1;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-10 col-md-9">
                            <div class="row align-items-end">
                                <div class="col-md-7 text-md-start text-center mt-4 mt-sm-0">
                                    <h3 class="title mb-0">@yield('section-title', $user->username)</h3>
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
                                            <h6 class="mb-0">@lang('profile.profile.menu.profile-text')</h6>
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
                                @livewire('profile.notificationtab')
                                @break
                            @case('settings')
                                @livewire('profile.settingstab')
                                @break
                            @default
                                @livewire('profile.profiletab')
                        @endswitch


                        {{--


                @include('homepage.members.profile.partials.profile')

                @include('homepage.members.profile.partials.notification')

                @include('homepage.members.profile.partials.settings')

                @include('homepage.members.profile.partials.teamspeak')

                @include('homepage.members.profile.partials.feedback')
                --}}
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
