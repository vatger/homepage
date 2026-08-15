@props([
    'id',
    'heading' => null,
    'text' => null,
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body py-5">
                <div class="text-center">
                    <div class="icon d-flex align-items-center justify-content-center bg-soft-danger rounded-circle mx-auto" style="height: 95px; width:95px;">
                        <h1 class="mb-0">
                            <i data-feather="alert-triangle"></i>
                        </h1>
                    </div>
                    <div class="mt-4">
                        @if($heading)
                            <h4>"{{ $heading }}"</h4>
                        @endif
                        @if($text)
                            <p class="text-muted">{{ $text }}</p>
                        @endif
                        {{ $slot ?? '' }}
                        <button type="button" class="btn btn-light btn-sm mt-3" data-bs-dismiss="modal">Abbrechen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
