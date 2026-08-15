@extends('layouts.admin.admin-master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">

            <x-layouts.admin.content
                header="Dashboard"
            ></x-layouts.admin.content>

            <div class="row row-cols-xl-3 row-cols-md-2 row-cols-1">

                <div class="col mt-4">
                    <a href="#!" class="features feature-primary d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon text-center rounded-pill">
                                <i class="mdi mdi-account-group fs-4 mb-0"></i>
                            </div>
                            <div class="flex-1 ms-3">
                                <h6 class="mb-0 text-muted">Registered Accounts</h6>
                                <p class="fs-5 text-dark fw-bold mb-0">{{ \App\Models\Membership\User::count() }}</p>
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

            <div class="card border-0 shadow rounded mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">External services</h5>
                            <p class="text-muted mb-0">Live connectivity status of configured integrations.</p>
                        </div>
                        <i class="mdi mdi-connection fs-4 text-muted"></i>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Service</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($externalServices as $service)
                                    @php
                                        $statusClass = match ($service['state']) {
                                            'up' => 'success',
                                            'not_configured' => 'secondary',
                                            default => 'danger',
                                        };
                                        $statusLabel = match ($service['state']) {
                                            'up' => 'Working',
                                            'not_configured' => 'Not configured',
                                            default => 'Unavailable',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $service['name'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}">
                                                <i class="mdi mdi-circle-small"></i> {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="text-end text-muted">{{ $service['detail'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
