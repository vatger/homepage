<div>

    @component('components.layouts.content',[
        'header' =>  $en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de,
        'subheader' => $policy->last_update,
        'links' => [
            route('landing') => config('app.name'),
            route('policy-list') => __('pages.policy.list-title'),
            $en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de
            ]
    ])
    @endcomponent

    <section class="section">
        <div class="container">
            @php
                $documentLanguage = $en && !empty($policy->path_en) ? 'en' : 'de';
                $alternativeLanguage = match ($documentLanguage) {
                    'de' => !empty($policy->path_en) ? 'en' : null,
                    'en' => !empty($policy->path_de) ? 'de' : null,
                };
            @endphp

            @if($alternativeLanguage)
                <div class="alert bg-soft-primary border border-primary d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"
                     role="note">
                    <div class="d-flex align-items-center">
                        <i data-feather="globe" class="fea icon-sm text-primary me-2 flex-shrink-0"></i>
                        <span>
                            {{ __('general.policy-language.notice', [
                                'language' => __('general.policy-language.languages.' . $alternativeLanguage),
                            ]) }}
                        </span>
                    </div>
                    <a href="{{ route('language.change', ['lang' => $alternativeLanguage]) }}"
                       class="btn btn-sm btn-outline-primary flex-shrink-0">
                        {{ __('general.policy-language.switch', [
                            'language' => __('general.policy-language.languages.' . $alternativeLanguage),
                        ]) }}
                    </a>
                </div>
            @endif

            @if (!empty($policy->path_changelog_de))
                <div class="container">
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">@lang('pages.policy.changelog')</h4>
                        {!! Storage::get($en && !empty($policy->path_changelog_en) ? $policy->path_changelog_en : $policy->path_changelog_de) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if($policy->type == "html")
                {!! Storage::get($en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de) !!}
            @endif
            @if($policy->type == "pdf")
                <object data="{{ $en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de }}" type="application/pdf" width="100%" height="1080px">
                    <p>
                        @lang('pages.policy.pdf-unavailable')
                        <a href="{{ $en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de }}">
                            @lang('pages.policy.download')
                        </a>
                    </p>
                </object>
            @endif
        </div>
    </section>
</div>
