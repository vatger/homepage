{{-- Events --}}
<!-- Section Start -->
<section class="section landing-events">
    <!-- Start Features -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <span class="landing-section-label">VATSIM Germany</span>
                    <h2 class="title mt-3 mb-3">@lang('landing.events.title')</h2>
                    <p class="text-muted para-desc mx-auto mb-0">@lang('landing.events.text')</p>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row" id="event-container">

            @for ($i = 0; $i < 9; $i++)
                <div class="col-lg-4 col-md-6 mb-4 pb-2 @if ($i > 5) hide @endif" id="event-{{ $i }}">
                    <a href="javascript:void(0)" id="event-readmore-{{ $i }}">
                        <div class="card blog landing-event-card border-0 overflow-hidden">
                            <div class="position-relative">
                                <div style="width: 100%; height: 100%; position: absolute" id="event-loader-{{ $i }}" class="event-loader-show">
                                </div>
                                <div class="overlay rounded-top"></div>
                                <div class="card-img-top loader-show overflow-hidden" id="event-banner-{{ $i }}"
                                     style="min-height: 200px; min-width: 356px"></div>
                            </div>
                            <div class="card-body content">
                                    <span class="badge rounded-pill bg-soft-primary mb-2" id="event-cpt-banner-{{ $i }}"
                                          style="display: none">Controller Practical
                                        Test</span>
                                <h5>
                                        <span class="card-title title text-dark" id="event-title-{{ $i }}">@lang('landing.events.loading-text')
                                        </span>
                                </h5>
                                <div class="post-meta d-flex justify-content-between mt-3">
                                    <ul class="list-unstyled mb-0">
                                        <li class="list-inline-item me-2 mb-0">
                                                <span href="javascript:void(0)" class="text-muted" id="event-date-{{ $i }}">
                                                    <i class="uil uil-heart me-1"></i>
                                                </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <!--end col-->
            @endfor

            <div style="text-align: center" class="mt-4 mb-0 pb-0" id="show-events-btn-container">
                <button type="button" class="btn btn-pills btn-soft-primary" id="show-events-btn" disabled> Show More</button>
            </div>
        </div>
        <!-- End Features -->
    </div>
</section>
<!--end section-->
<!-- section End -->

@push('scripts')
    @vite(['resources/ts/special/events.ts'])
@endpush
