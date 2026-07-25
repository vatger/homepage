@props([
    'ident',
    'caption',
    'date',
    'agreed_date' => null,
    'path',
    'type',
    'changelog' => null
])

@php
    $agreed_date = $agreed_date ? \Carbon\Carbon::create($agreed_date) : null;

@endphp

<div class="accordion-item rounded mt-2">
    <h2 class="accordion-header" id="{{ $ident }}-h">
        <button class="accordion-button border-0 bg-light collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $ident }}-d" aria-expanded="false"
                aria-controls="{{ $ident }}-d">
            <span class="text">{{ $caption }} </span>
            <span class="text-secondary ms-4">@lang('pages.policy.last-revised')</span>
            <span class="text ms-2">{{ $date->translatedFormat('j. F Y') }}</span>
        </button>
    </h2>
    <div id="{{ $ident }}-d" class="accordion-collapse border-0 collapse" aria-labelledby="{{ $ident }}-h" data-bs-parent="#{{ $ident }}-h" style="">
        <div class="accordion-body">
            @if(!empty($changelog))
                <div class="container">
                    <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">@lang('pages.policy.changelog')</h4>
                        {!! Storage::get($changelog) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('general.phrases.cancel') }}"></button>
                    </div>
                </div>
            @endif
            @if($type == "html")
                {!! Storage::get($path) !!}
            @endif
            @if($type == "pdf")
                <object data="{{ $path }}" type="application/pdf" width="100%" height="800px">
                    <p>
                        @lang('pages.policy.pdf-unavailable')
                        <a href="{{ $path }}">@lang('pages.policy.download')</a>
                    </p>
                </object>
            @endif
        </div>
    </div>
</div>

<div class="mt-3 mb-4">
    <button wire:click="accept('{{ $ident }}')" class="btn btn-primary mt-2 me-2">@lang('pages.policy.accept')</button>
    <button wire:click="decline('{{ $ident }}')" class="btn btn-outline-primary mt-2">@lang('pages.policy.decline')</button>

    @if($agreed_date && $agreed_date->isAfter($date))
        <div class="text-success mt-1">
            {{ __('pages.policy.accepted-at', ['date' => $agreed_date->translatedFormat('j. F Y H:i')]) }}
        </div>
    @elseif($agreed_date)
        <div class="text-warning mt-1">
            {{ __('pages.policy.accepted-at', ['date' => $agreed_date->translatedFormat('j. F Y H:i')]) }}
        </div>
    @else
        <div class="text-warning mt-1">@lang('pages.policy.not-accepted')</div>
    @endif
</div>
