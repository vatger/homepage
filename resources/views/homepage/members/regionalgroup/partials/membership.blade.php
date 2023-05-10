<div class="tab-pane fade bg-white p-4 rounded shadow" id="apply-tab" role="tabpanel" aria-labelledby="apply">
    <h5>@lang('regionalgroup.regionalgroup.membership-text')</h5>

    <div class="row">
        <form action="{{ route('member.regionalgroup.request') }}" method="post" id="change-membership-form">
            @csrf
            <input type="hidden" name="regionalgroup" value="{{ $regionalgroup->id }}">
            <div class="col-md-12 mt-2">
                <label class="form-label" for="type">Change Membership Type :</label>
                <select name="type" id="type" class="form-control">
                    @if (!$_user->isMemberOfRegionalGroup($regionalgroup))
                        <option value="member">@lang('general.vatger.full-member')</option>
                    @endif
                    @if (!$_user->isGuestOfRegionalGroup($regionalgroup))
                        <option value="guest">@lang('general.vatger.guest-member')</option>
                    @endif
                </select>
            </div>
            <div class="col-md-12 mt-4">
                <div class="mb-3">
                    <label class="form-label">Reason / Message</label>
                    <div class="form-icon position-relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text fea icon-sm icons">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <textarea name="reason" id="reason" rows="4" class="form-control ps-5" placeholder="Message"></textarea>
                    </div>
                </div>
            </div>
            <button type="button" id="submit-membership-change" class="mt-3 btn btn-soft-primary">Request Membership Type
                Change</button>
        </form>
    </div>

    <div class="row mt-4 border-top">
        <h5 class="mt-4">(lang) Cancel Membership</h5>

        <div class="col-md-12">
            <p class="text-muted">
                (lang) With the button below you can cancel your membership with this regionalgroup.
                Please be aware that this action can <u>not</u> be undone and all ATC training progress will be lost. Also any
                related major ATC endorsement and solo endorsements will be revoked.
            </p>
            <button type="button" class="btn btn-soft-danger mt-3" data-bs-toggle="modal" data-bs-target="#leave-rg-modal">(lang) Cancel
                Membership</button>
        </div>
    </div>
</div>
<!--end tab pane-->

<div class="modal fade" id="leave-rg-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-body py-5">
                <div class="text-center">
                    <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto"
                        style="height: 95px; width:95px;">
                        <h1 class="mb-0"><svg style="width: 44px; height: 44px; margin-top: -9px; margin-left: 0px"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-alert-triangle">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg></h1>
                    </div>
                    <div class="mt-4">
                        <h4>(lang) Are you sure?</h4>
                        <p class="text-muted">(lang) With the button below you can cancel your membership with this regionalgroup.
                            Please be aware that this action can <u>not</u> be undone and all ATC training progress will be lost. Also any
                            related major ATC endorsement and solo endorsements will be revoked.</p>
                        <div class="mt-4">
                            <form action="{{ route('member.regionalgroup.delete', ['regionalgroup' => $regionalgroup->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft-danger">(lang) Cancel Membership</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('custom-script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/f5oxwmdtukvy1qwch4b3ghpazlyw2rzjxsljjdiis3kedxhg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $("#submit-membership-change").click(() => {
            tinyMCE.triggerSave();
            if ($(`${$("#reason").val()}`).text().length < 25) {
                new Noty({
                    text: '@lang('regionalgroup.apply.membership-type.25-chars')',
                    progressBar: true,
                    modal: false,
                    timeout: 2000,
                    layout: 'topRight',
                    type: 'error',
                }).show();
            } else {
                $("#change-membership-form").submit();
            }
        });

        tinymce.init({
            selector: 'textarea',
            @if (\Auth::user()->settings->dark_mode)
                skin: 'oxide-dark',
                content_css: 'dark',
            @endif
            plugins: '',
            menubar: 'false',
            toolbar_mode: 'floating',
        });
    </script>
@endpush
