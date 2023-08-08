@if(!auth()->check() || !Auth::user()->settings->dark_mode)
    @vite('resources/scss/app.scss')
@else
    @vite('resources/scss/app-dark.scss')
@endif
@php
    $c = Auth::check() ?? 'resources/css/'. Auth::user()->settings->color .'.css';
    $v = false;
    try {
        $v = !! \Illuminate\Support\Facades\Vite::asset($c);
    } catch (Exception $e){}
@endphp
@if($c && $v)
    @vite($c)
@else
    @vite('resources/css/default.css')
@endif
