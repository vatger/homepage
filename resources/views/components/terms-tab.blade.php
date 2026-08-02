@props([
    'ident',
    'caption',
    'date',
    'agreed_date' => null,
    'path',
    'type',
    'changelog' => null,
])

@php
    $agreedDate = $agreed_date ? \Carbon\Carbon::create($agreed_date) : null;
    $isCurrent = $agreedDate?->isAfter($date) ?? false;
@endphp

<details class="group" {{ $isCurrent ? '' : 'open' }}>
    <summary class="flex cursor-pointer list-none items-start justify-between gap-5 rounded-2xl text-left marker:content-none">
        <span>
            <span class="block text-lg font-bold text-primary-900 dark:text-secondary-50">{{ $caption }}</span>
            <span class="mt-1 block text-sm text-secondary-500 dark:text-secondary-300">
                @lang('pages.policy.last-revised') {{ $date->translatedFormat('j. F Y') }}
            </span>
        </span>
        <span class="mt-1 shrink-0 rounded-full border border-secondary-200 px-3 py-1 text-xs font-bold text-primary-700 transition group-open:rotate-45 dark:border-secondary-700 dark:text-secondary-100">+</span>
    </summary>

    <div class="mt-6 border-t border-secondary-200 pt-6 dark:border-secondary-700">
        @if(!empty($changelog))
            <div class="surface-muted mb-5 p-4 text-sm text-secondary-700 dark:text-secondary-200">
                <h3 class="font-bold text-primary-900 dark:text-secondary-50">@lang('pages.policy.changelog')</h3>
                <div class="prose prose-sm mt-3 max-w-none dark:prose-invert">{!! Storage::get($changelog) !!}</div>
            </div>
        @endif

        @if($type === 'html')
            <div class="prose max-w-none dark:prose-invert">{!! Storage::get($path) !!}</div>
        @elseif($type === 'pdf')
            <iframe src="{{ $path }}" class="h-[42rem] w-full rounded-2xl border border-secondary-200 dark:border-secondary-700" title="{{ $caption }}"></iframe>
            <p class="mt-3 text-sm text-secondary-500 dark:text-secondary-300">
                @lang('pages.policy.pdf-unavailable')
                <a href="{{ $path }}" class="link">@lang('pages.policy.download')</a>
            </p>
        @endif
    </div>
</details>

<div class="mt-6 flex flex-wrap items-center gap-3 border-t border-secondary-200 pt-5 dark:border-secondary-700">
    <form method="POST" action="{{ route('check-terms.update', ['policyId' => $ident]) }}">
        @csrf
        <button type="submit" name="decision" value="accept" class="btn btn-primary">
            @lang('pages.policy.accept')
        </button>
    </form>
    <form method="POST" action="{{ route('check-terms.update', ['policyId' => $ident]) }}">
        @csrf
        <button type="submit" name="decision" value="decline" class="btn border-secondary-300 bg-white text-primary-900 hover:bg-secondary-100 dark:border-secondary-600 dark:bg-secondary-800 dark:text-secondary-50 dark:hover:bg-secondary-700">
            @lang('pages.policy.decline')
        </button>
    </form>

    @if($agreedDate)
        <span @class([
            'ml-auto text-sm font-semibold',
            'text-emerald-700 dark:text-emerald-300' => $isCurrent,
            'text-amber-700 dark:text-amber-300' => ! $isCurrent,
        ])>
            {{ __('pages.policy.accepted-at', ['date' => $agreedDate->translatedFormat('j. F Y H:i')]) }}
        </span>
    @else
        <span class="ml-auto text-sm font-semibold text-amber-700 dark:text-amber-300">@lang('pages.policy.not-accepted')</span>
    @endif
</div>
