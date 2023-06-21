<div class="tab-pane fade bg-white p-4 rounded shadow" id="feedback-tab" role="tabpanel" aria-labelledby="feedback">
    <h5 class="text-md-start text-center mt-3">@lang('profile.profile.feedback.feedback-access-text'):</h5>
    @if (Auth::user()->controllerFeedback != null && sizeof(Auth::user()->controllerFeedback) > 0)
        <div class="timeline-page pt-2 position-relative">
            @foreach (Auth::user()->controllerFeedback as $cf)
                @if ($loop->index % 2 === 0)
                    <div class="timeline-item mt-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 order-sm-1 order-2">
                                <div class="card event event-description-left rounded shadow border-0 overflow-hidden float-start"
                                    style="float: right !important;">
                                    <div class="card-body">
                                        <h5 class="mb-0 text-capitalize">{{ $cf->station_id }}</h5>
                                        <p class="mt-3 mb-0 text-muted">{{ $cf->feedback }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-6 col-md-6 col-sm-6 order-sm-2 order-1">
                                <div class="duration duration-right rounded border p-2 px-4 position-relative shadow text-start">
                                    {{ $cf->report_date->format('d.m.Y, H:m') }}</div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                @else
                    <div class="timeline-item mt-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="duration date-label-left border rounded p-2 px-4 position-relative shadow">
                                    {{ $cf->report_date->format('d.m.Y, H:m') }}</div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="card event event-description-right rounded shadow border-0 overflow-hidden float-start">
                                    <div class="card-body">
                                        <h5 class="mb-0 text-capitalize">{{ $cf->station_id }}</h5>
                                        <p class="mt-3 mb-0 text-muted">{{ $cf->feedback }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                @endif
            @endforeach
        </div>
        <!--end timeline page-->
    @else
        <div class="alert alert-danger mt-3" role="alert">@lang('profile.profile.feedback.no-feedback')</div>
    @endif
    <!-- TIMELINE END -->
</div>
