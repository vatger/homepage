<div>
    <section class="relative overflow-hidden bg-primary-900 py-16 text-white sm:py-20">
        <div class="site-container relative">
            <span class="landing-kicker">vatger</span>
            <h1 class="mt-5 text-3xl font-bold tracking-tight sm:text-4xl">@lang('pages.policy.important')</h1>
            <p class="mt-3 max-w-2xl text-secondary-200">@lang('pages.policy.list-title')</p>
        </div>
    </section>

    <section class="section">
        <div class="site-container max-w-4xl">
            <div class="space-y-5">
                @forelse($polices as $policy)
                    <section class="surface p-5 sm:p-7">
                        <x-terms-tab
                            :ident="$policy->id"
                            :caption="$en && !empty($policy->name_en) ? $policy->name_en : $policy->name_de"
                            :date="\Carbon\Carbon::create($policy->last_update)"
                            :path="$en && !empty($policy->path_en) ? $policy->path_en : $policy->path_de"
                            :agreed_date="$user_settings->getAgreedAt($policy->id)"
                            :type="$policy->type"
                            :changelog="$en && !empty($policy->path_changelog_en) ? $policy->path_changelog_en : $policy->path_changelog_de ?? null"
                        />
                    </section>
                @empty
                    <div class="surface p-6 text-secondary-600 dark:text-secondary-300">
                        @lang('pages.policy.not-accepted')
                    </div>
                @endforelse

                @if($user_settings->agreed)
                    <div class="flex justify-end pt-2">
                        <a href="{{ $url ? urldecode($url) : route('landing') }}" class="btn btn-primary px-6">
                            @lang('pages.policy.continue')
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
