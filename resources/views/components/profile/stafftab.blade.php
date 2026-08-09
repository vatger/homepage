<div class="tab-pane profile-staff-panel fade active show p-5 sm:p-8" role="tabpanel" aria-labelledby="staff">
    <div class="mt-4">
        <h5 class="text-md-start text-center">@lang('sdp.text-header'):</h5>
        <h4 class="alert-heading">@lang('sdp.text-welcome-header')</h4>
        <p>@lang('sdp.text-welcome-hedaer-sub')</p>
        <p class="mb-0 border-top pt-3">@lang('sdp.text-sdp')</p>
        <hr>
        Zugestimmt am {{ Auth::user()?->staffDetails?->accepted_data_protection_at->format('d.m.Y') }}.

        <hr>
        <div class="mt-4">
            <label for="staff-name-format" class="form-label">
                @lang('sdp.name-format')
            </label>
            <select id="staff-name-format" wire:model.live="staff_name_format" class="form-select">
                @foreach(\App\Models\Membership\StaffNameFormat::cases() as $format)
                    <option value="{{ $format->value }}">
                        @lang('sdp.name-format-'.$format->value)
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">@lang('sdp.name-format-help')</small>
        </div>

    </div>
</div>
