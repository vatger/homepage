@props(['title', 'text', 'subtext' => null, 'feaicon'])
<div class="profile-detail-item d-flex align-items-center">
    <span class="profile-detail-icon">
        <i data-feather="{{ $feaicon }}" aria-hidden="true"></i>
    </span>
    <div class="flex-grow-1 min-w-0">
        <span class="profile-detail-label">{{ $title }}</span>
        <div class="profile-detail-value">
            {{ $text ? : '-' }} {{ $subtext ? '('. $subtext .')': '' }}
        </div>
        {{ $slot }}
    </div>
</div>
