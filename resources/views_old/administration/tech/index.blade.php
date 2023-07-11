@extends('administration.partials.master')

@section('content')
    <div class="container-fluid">
        <div class="layout-specing">
            <div class="d-md-flex justify-content-between align-items-center">
                <h5 class="mb-0">Scheduled Updates</h5>

                <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
                    <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                        <li class="breadcrumb-item text-capitalize"><a href="{{ route('administration.dashboard') }}">Administration</a></li>
                        <li class="breadcrumb-item text-capitalize active" aria-current="page">Scheduled Updates</li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col-12">
                    <pre><code>{{ json_encode($schedule_events, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>

        </div>
    </div>
@endsection
