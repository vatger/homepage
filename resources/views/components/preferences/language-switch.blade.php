@php($currentLanguage = Session::get('language', 'en'))

<div class="preference-switch" role="group" aria-label="Language">
    <a href="{{ route('language.change', ['lang' => 'de']) }}"
       class="preference-switch-option {{ $currentLanguage === 'de' ? 'is-active' : '' }}"
       aria-label="Deutsch" title="Deutsch" @if($currentLanguage === 'de') aria-current="true" @endif>
        <img src="{{ asset('images/germany.svg') }}" class="h-4 w-6 rounded-sm object-cover" alt="" aria-hidden="true">
        <span class="sr-only">Deutsch</span>
    </a>
    <a href="{{ route('language.change', ['lang' => 'en']) }}"
       class="preference-switch-option {{ $currentLanguage === 'en' ? 'is-active' : '' }}"
       aria-label="English" title="English" @if($currentLanguage === 'en') aria-current="true" @endif>
        <img src="{{ asset('images/united-kingdom.svg') }}" class="h-4 w-6 rounded-sm object-cover" alt="" aria-hidden="true">
        <span class="sr-only">English</span>
    </a>
</div>
