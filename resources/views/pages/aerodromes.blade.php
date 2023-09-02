<div>
    <section class="bg-half-170 bg-light d-table w-100" style='background-image: url(" @yield('hero-img-src', asset('images/getstarted/getstarted_1.png')) ")'>
        <div class="bg-overlay" style="background-color: rgb(30 41 58 / 85%)"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="pages-heading">
                        <h2 style="color: white">@lang('pilot.aerodromes.title')</h2>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="position-breadcrumb">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ul class="breadcrumb bg-white rounded shadow mb-0 px-4 py-2">
                        <li class="breadcrumb-item"><a href="{{ route('landing') }}">{{ config('app.name') }}</a></li>
                        <li class="breadcrumb-item">Pilots</li>
                        <li class="breadcrumb-item">@lang('pilot.aerodromes.title')</li>
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


    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 mt-4 col-12">
                    <div class="card features rounded p-4 bg-white shadow position-relative overflow-hidden border-0">
                        <div class="card-body p-0 content">
                            <div class="mb-3">
                                <label class="form-label">@lang('pilot.aerodromes.search-text')</label>
                                <div class="form-icon position-relative">
                                    <i data-feather="book" class="fea icon-sm icons"></i>
                                    <input wire:model.live="search" name="subject" class="form-control ps-5" type="text"
                                           placeholder="@lang('pilot.aerodromes.search-input-placeholder')">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="">
                                <ul class="container-filter list-inline mb-0 filter-options text-center">
                                    <li class="list-inline-item categories-name border text-dark rounded @if($selected_fir == -1) active @endif" wire:click="fir_select({{ -1 }})">
                                        All
                                    </li>
                                    @foreach ($firs as $fir)
                                        <li class="list-inline-item border text-dark rounded @if($selected_fir == $fir->id) active @endif" wire:click="fir_select({{ $fir->id }})">
                                            {{ $fir->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!--end col-->
                        <p class="text-muted mb-0" style="display: none" id="search-count-container">Die Suche ergab <span id="search-count"></span>
                            Treffer.</p>
                    </div>
                </div>
                <!--end col-->
            </div>

            <div class="row mb-1 text-center">
                <div class="row" style="padding-right: 0 !important; left: 5px !important;">
                    @foreach($aerodromes as $aerodrome)
                        <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2 picture-item">
                            <a wire:click="aerodrome_select({{ $aerodrome->id }})">
                                <div class="card blog border-0 work-container work-primary work-classic shadow rounded-md overflow-hidden">
                                    <div class="card-body">
                                        <div class="content">
                                            <h5><span class="text-dark title">{{ $aerodrome->icao }} {{ $aerodrome->iata ? ' | ' . $aerodrome->iata : '' }} </span></h5>
                                            <p class="text-muted mb-0">{{ $aerodrome->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!--end col-->
                    @endforeach
                </div>
                <div class="row justify-content-center">
                    {{ $aerodromes->links() }}
                </div>
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
</div>
