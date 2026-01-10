@props(['title', 'text', 'subtext' => null, 'feaicon'])
<div class="d-flex align-items-center mt-3">
    <i data-feather="{{ $feaicon }}" class="fea text-muted me-3"></i>
    <div class="flex-1">
        <h6 class="text-primary mb-0">{{ $title }}:</h6>
        <a class="text-muted">
            {{ $text ? : '-' }} {{ $subtext ? '('. $subtext .')': '' }}
        </a>
        {{ $slot }}
    </div>
</div>
