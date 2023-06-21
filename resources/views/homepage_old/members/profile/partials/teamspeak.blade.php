<div class="tab-pane fade bg-white p-4 rounded shadow" id="teamspeak-tab" role="tabpanel" aria-labelledby="teamspeak">
    <h5 class="text-md-start text-center">@lang('profile.profile.teamspeak.teamspeak-access-text'):</h5>

    <div class="p-4 table-responsive">
        <table class="w-100 table table-center">
            <thead>
                <tr>
                    <th class="text-center fw-bold border-bottom w-25">TS-ID</th>
                    <th class="text-center fw-bold border-bottom w-25">@lang('profile.profile.teamspeak.last-ip-text')</th>
                    <th class="text-center fw-bold border-bottom w-25">@lang('profile.profile.teamspeak.last-used-text')</th>
                    <th class="text-center fw-bold border-bottom w-25">@lang('profile.profile.teamspeak.remove-button-content')</th>
                </tr>
            </thead>
            <tbody id="teamspeak-registrations-container">
                @foreach (Auth::user()->teamspeakRegistrations as $tsReg)
                    <tr>
                        <td class="text-center"><a data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $tsReg->uid }}"
                                alt="">{{ substr($tsReg->uid, 0, 11) }}...</a></td>
                        <td class="text-center">{{ $tsReg->last_ip }}</td>
                        <td class="text-center"><a data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $tsReg->last_login->format('d.m.Y H:m') }}z" alt="">{{ $tsReg->last_login->format('d.m.Y') }}</a>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-soft-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-trash-2 icon-ex-md fea text-dark mb-1">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <button class="btn btn-soft-primary" type="button" id="ts-manual-reg-button">Manuelle Registrierung</button>

    <div id="ts-manual-reg-container" style="display: none">
        <h5 class="text-md-start text-center pt-4 border-top">@lang('profile.profile.teamspeak.manual-registration.title'):</h5>

        <div class="p-2 table-responsive">
            <div class="alert alert-light shadow" id="event-routes" role="alert">
                @lang('profile.profile.teamspeak.manual-registration.information-text.0')
            </div>

            <input class="form-control mb-2" type="text" placeholder="TS-ID / Identität" id="ts-manual-reg-tsid">

            <button class="btn mt-2 btn-soft-primary" id="ts-manual-reg-submit-btn">@lang('profile.profile.teamspeak.manual-registration.complete-button-content')</button>
            <button class="btn mt-2 btn-soft-danger" type="button" id="ts-manual-reg-cancel-btn">@lang('general.phrases.cancel')</button>
        </div>
    </div>
</div>
<!--end tab pane-->

@push('custom-script')
    <script>
        $("#ts-manual-reg-button").click(function() {
            $("#ts-manual-reg-button").css('display', 'none');
            $("#ts-manual-reg-container").css('display', 'block');
        });

        $("#ts-manual-reg-cancel-btn").click(() => {
            $("#ts-manual-reg-button").css('display', 'block');
            $("#ts-manual-reg-container").css('display', 'none');
        });
    </script>

    <script>
        $("#ts-manual-reg-submit-btn").click(() => {
            let tsId = $("#ts-manual-reg-tsid").val();
            let actionUrl = "{{ route('api.membership.teamspeak.submitregistration') }}";

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: {
                    _token: "{{ csrf_token() }}",
                    uid: tsId,
                },
                success: function(data) {
                    showNoty('YYYYY', 'success', 2000);
                    $("#teamspeak-registrations-container")
                        .append(`<tr>
                            <td class="text-center"><a data-bs-toggle="tooltip" data-bs-placement="top" title="${tsId}" alt="">${tsId.substring(0,11)}...</a></td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-soft-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon-ex-md fea mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>
                            </td>
                        </tr>`);

                    $("#ts-manual-reg-tsid").val('');

                    $("[data-bs-toggle='tooltip']").tooltip();
                },
                error: function(xhr, http, error) {
                    showNoty('NNNN', 'error', 2000);
                }
            })
        });
    </script>
@endpush
