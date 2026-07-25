<div class="pb-4">
    <h5>@lang('pages.getting-started.vatsim.title')</h5>
    <p class="text-muted mb-0">@lang('pages.getting-started.vatsim.intro')</p>

    <p class="text-muted mt-3">{!! __('pages.getting-started.vatsim.create') !!}</p>

    <ol class="text-muted">
        <li>@lang('pages.getting-started.vatsim.region')</li>
        <li>@lang('pages.getting-started.vatsim.division')</li>
    </ol>

    <p class="text-muted">@lang('pages.getting-started.vatsim.credentials')</p>

    <div class="alert alert-danger">@lang('pages.getting-started.vatsim.warning')</div>

    <div class="border-bottom"></div>

    <ul class="text-muted mt-3">
        <li>
            {!! __('pages.getting-started.vatsim.password') !!}
        </li>
        <li>
            {!! __('pages.getting-started.vatsim.membership') !!}
        </li>
        <li>
            {!! __('pages.getting-started.vatsim.reactivate') !!}
        </li>
    </ul>

    <div class="d-flex justify-content-end">
        <button class="btn btn-primary mt-4">@lang('pages.getting-started.continue')</button>
    </div>
</div>
