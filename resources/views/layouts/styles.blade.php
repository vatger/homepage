@if(!auth()->check() || !Auth::user()->settings->dark_mode)
    @vite('resources/scss/app.scss')
@else
    @vite('resources/scss/app-dark.scss')
@endif
{{--
@if(auth()->check())
    @switch(Auth::user()->settings->color)

    @endswitch
@endif
--}}
