<form action="{{ route('controllers.feedback.submit') }}" method="POST" class="pb-3">
    @csrf
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">@lang('controller.feedback.user-id-text')</label>
                <div class="form-icon position-relative">
                    <div id="user-search-status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <input name="user-cid" id="user-cid" type="text" class="form-control ps-5" placeholder="1373921">
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="user-name">@lang('controller.feedback.user-name-text')</label>
                <div class="form-icon position-relative">
                    <div id="user-search-status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <input name="user-name" id="user-name" type="text" class="form-control ps-5" placeholder="Max Mustermann">
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="subject">ATC-Position</label>
                <div class="form-icon position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user fea icon-sm icons">
                        <circle cx="12" cy="12" r="2"></circle>
                        <path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14">
                        </path>
                    </svg>
                    <input name="subject" id="subject" class="form-control ps-5" placeholder="EDDF_N_APP" style="text-transform: uppercase">
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label" for="date-select">@lang('controller.feedback.date-text') <span class="text-danger">*</span></label>
                <div class="form-icon position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar fea icon-sm icons">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <input name="report-date" id="date-select" type="text" class="form-control ps-5"
                        value="{{ \Carbon\Carbon::now()->format('d.m.Y H:i') }}">
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="comments">Feedback <span class="text-danger">*</span></label>
                <div class="form-icon position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-message-circle fea icon-sm icons">
                        <path
                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                        </path>
                    </svg>
                    <textarea disabled name="feedback" id="feedback" rows="4" class="form-control ps-5"></textarea>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    <div class="row">
        <div class="col-sm-12">
            <input type="button" id="submit" name="send" class="btn btn-soft-primary" value="@lang('controller.feedback.send-feedback-button-content')">
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</form>
