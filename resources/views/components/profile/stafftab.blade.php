<div class="tab-pane fade bg-white p-4 rounded shadow active show" role="tabpanel" aria-labelledby="staff">
    <div class="mt-4">
        <h5 class="text-md-start text-center">@lang('sdp.text-header'):</h5>
        <h4 class="alert-heading">@lang('sdp.text-welcome-header')</h4>
        <p>@lang('sdp.text-welcome-hedaer-sub')</p>
        <p class="mb-0 border-top pt-3">@lang('sdp.text-sdp')</p>
        <hr>
        Zugestimmt am {{ Auth::user()?->staffDetails?->accepted_data_protection_at->format('d.m.Y') }}.

    </div>
</div>
