@extends('homepage.partials.master')

@section('content')
    <!-- Hero Start -->
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url("{{ asset('images/hero-banners/hero_8.png') }}")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 70%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">EuroScope Sectorfiles</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">VATGER</a></li>
                        <li class="breadcrumb-item active" aria-current="page">EuroScope Sectorfiles</li>
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
                            Dies ist ein kombiniertes Sectorfile. Der Inhalt setzt sich aus den von GNG bereitgestellten SCT und ESE Dateien aller
                            Regionalgruppen zusammen.
                            Es besteht kein Anspruch auf Vollständigkeit oder Richtigkeit der Inhalte. Die Daten werden in einem teilautomatisierten
                            Verfahren erzeugt.
                            <span class="bg-soft-danger text-dark text-bold">Es wird davon abgeraten dieses Sectorfile zum aktiven Lotsen zu
                                verwenden!</span>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('euroscope.sectorfile.download') }}" class="btn btn-block btn-secondary">Download</a>
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
