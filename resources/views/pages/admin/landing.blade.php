@extends('layouts.admin-master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dashboard</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Dashboard</li>
                    </ul>
                </nav>
            </div>

            <div class="row row-cols-xl-3 row-cols-md-2 row-cols-1">

                <div class="col mt-4">
                    <a href="#!" class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-account-group fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Registered Accounts</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ \App\Models\Membership\User\User::count() }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->

                <div class="col mt-4">
                    <a href="#!" class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-airport fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Aerodromes</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ \App\Models\Navigation\Aerodrome::isDe()->count() }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->

                <div class="col mt-4">
                    <a href="#!" class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-radio-tower fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">ATC Stations</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ \App\Models\Navigation\Station::count() }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>
@endsection
