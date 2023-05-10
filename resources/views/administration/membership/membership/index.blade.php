@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mitgliederverwaltung</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Mitgliederverwaltung</li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col mt-4">
                    <div class="card shadow border-0">
                        @livewire(\App\Http\Livewire\Administration\Membership\Membership\Memberlist::class)

                    </div>
                </div>
                <!--end col-->
            </div>
        </div>
    </div>

    <style>
        .row {
            --bs-gutter-x: 0 !important;
            margin-right: 0 !important;
            margin-left: 0 !important;
        }
    </style>
@endsection
