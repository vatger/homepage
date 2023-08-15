@props([
    'ident',
    'caption',
    'date',
    'text'
])

<div class="accordion-item rounded mt-2">
    <h2 class="accordion-header" id="{{ $ident }}-h">
        <button class="accordion-button border-0 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $ident }}-d" aria-expanded="false"
                aria-controls="{{ $ident }}-d">
            <span class="text">{{ $caption }} </span>
            <span class="text-secondary ms-4"> Last Revised: </span>
            <span class="text ms-2">{{ $date->format('jS F Y') }}</span>
        </button>
    </h2>
    <div id="{{ $ident }}-d" class="accordion-collapse border-0 collapse" aria-labelledby="{{ $ident }}-h" data-bs-parent="#{{ $ident }}-h" style="">
        <div class="accordion-body">
            {!! $text !!}
        </div>
    </div>
</div>

<div class="mb-4">
    <a wire:click="accept('{{ $ident }}')" class="btn btn-primary mt-2 me-2">Accept</a>
    <a wire:click="decline('{{ $ident }}')" class="btn btn-outline-primary mt-2">Decline</a>
</div>
