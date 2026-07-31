@props(['partners'])

<section class="section landing-partners border-top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="section-title mb-4 pb-2">
                    <span class="landing-section-label">@lang('pages.landing.community')</span>
                    <h2 class="title mt-3 mb-3">@lang('landing.partner.title')</h2>
                    <p class="text-muted para-desc mx-auto mb-0">@lang('landing.partner.text')</p>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            @foreach ($partners as $partner)
                <div class="col-lg-2 col-md-3 col-6 text-center p-3"
                     data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $partner->name }}">
                    <a href="{{ $partner->link_url }}" class="landing-partner d-flex align-items-center justify-content-center">
                        <img src="{{ iasset($partner->logo_url, 160*2)}}" width="160" class="img-fluid" alt="{{ $partner->name }}">
                    </a>
                </div><!--end col-->
            @endforeach
        </div><!--end row-->
    </div><!--end container-->
</section>


{{--
@if ($partners->count() > 0)
    <section class="section mt-0 pt-3 pb-5 mb-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <div class="section-title mb-4 pb-2">
                        <h4 class="title mb-1">@lang('pages.landing.partners-title')</h4>
                        <p class="text-muted mb-0 pb-0">
                            @lang('pages.landing.partners-text')
                            <a href="#" class="link">@lang('pages.landing.partners-link')</a>.
                        </p>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-12 mt-0">
                    <div
                        class="@if ($partners->count() == 1) tiny-one-item @elseif($partners->count() == 2) tiny-two-item @else tiny-three-item @endif">
                        @foreach ($partners as $partner)
                            <a href="https://google.de">
                                <div class="tiny-slide text-center">
                                    <div class="client-testi rounded shadow m-2 p-4">
                                        <img src="{{ $partner->logo_url }}" class="img-fluid avatar avatar-small"
                                             style="max-height: 65px; width: auto;" alt="">
                                        <p class="text-start mt-3 mb-0 text-dark"><strong>{{ $partner->name }}</strong></p>
                                        <div class="text-muted text-start" id="description-text">
                                            @if (strlen($partner->description) > 40)
                                                {!! substr($partner->description, 0, 40) . '...' !!}
                                            @else
                                                {!! $partner->description !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>
    <!--end section-->

    <style>
        .client-testi {
            cursor: pointer !important;
        }

        #description-text > p {
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }
    </style>
@endif
--}}
