@extends('layouts.master')

@section('content')
    @component('components.layouts.content',[
       'header' => "Erste Schritte",
       'links' => [
           route('landing') => config('app.name'),
       'Erste Schritte',
       ],
   ])
    @endcomponent

    <div class="container mt-100 pb-5">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 d-lg-block d-none">
                <div class="sidebar sticky-bar p-4 rounded shadow">
                    <div class="widget border-bottom pb-4">
                        <div class="progress-box mt-2">
                            <p class="title text-muted mb-2">0/4 Abgeschlossen</p>

                            <div class="progress">
                                <div class="progress-bar position-relative bg-primary" style="width: 50%;"></div>
                            </div>
                        </div><!--end process box-->
                    </div>

                    <div class="widget mt-2">
                        <ul class="list-unstyled sidebar-nav mb-0" id="navmenu-nav">
                            <li class="navbar-item account-menu px-0">
                                <a href="account-profile.html" class="d-flex rounded shadow align-items-center py-2 px-2">
                                    <h6 class="mb-0 ms-2">1. Registrierung VATSIM</h6>
                                </a>
                            </li>

                            <!-- Class text-success if <a> is completed -->

                            <li class="navbar-item account-menu px-0 mt-2">
                                <a href="account-profile.html" class="text-success d-flex rounded shadow align-items-center py-2 px-2">
                                    <h6 class="mb-0 ms-2">2. Registrierung VATSIM Germany</h6>
                                </a>
                            </li>

                            <li class="navbar-item account-menu px-0 mt-2">
                                <a href="account-profile.html" class="text-muted d-flex rounded shadow align-items-center py-2 px-2">
                                    <h6 class="mb-0 ms-2">3. New Member Orientation Test</h6>
                                </a>
                            </li>

                            <li class="navbar-item account-menu px-0 mt-2">
                                <a href="account-profile.html" class="text-muted d-flex rounded shadow align-items-center py-2 px-2">
                                    <h6 class="mb-0 ms-2">4. Zuordnung EMEA / EUD / GER</h6>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-8 col-12">
                @component('components.getting-started.vatsim-registration')
                @endcomponent
            </div><!--end col-->
        </div><!--end row-->
    </div>
@endsection
