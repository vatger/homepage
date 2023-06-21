@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{ asset('images/hero-banners/hero_8.png') }}")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">EuroScope SimSessions</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">VATGER</a></li>
                        <li class="breadcrumb-item active" aria-current="page">EuroScope SimSessions</li>
                    </ul>
                </nav>
            </div>
        </div>
        <!--end container-->
    </section>
    <!--end section-->
    <!-- Hero End -->

    <!-- Shape Start -->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!--Shape End-->

    <!-- Blog STart -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow border-0 rounded">
                        <div class="card-body table-responsive">
                            <table class="table table-center">
                                <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th class="justify-content-end align-content-end">
                                            <a href="{{ route('euroscope.scenarios.create') }}" class="btn btn-sm btn-secondary">
                                                Create New Scenario
                                            </a>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scenarios as $s)
                                        <tr>
                                            <td>{{ $s['name'] }}</td>
                                            <td>{{ $s['date'] }}</td>
                                            <td><a href="{{ route('euroscope.scenarios.show', $s['name']) }}" class="btn btn-soft-blue btn-sm">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!-- Blog End -->
@endsection
