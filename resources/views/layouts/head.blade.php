<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="title" content="{{ config('app.name') }}">
<meta name="author" content="vACC Germany">
<meta name="description" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="https://vatsim-germany.org">
<meta name="lang" content="{{ app()->getLocale() }}">
<meta name="color-scheme" content="{{ (!auth()->check() || !Auth::user()->settings->dark_mode) ? 'light' : 'dark' }}">

<!-- favicon -->
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

@if(!auth()->check() || !Auth::user()->settings->dark_mode)
    @if(isset($_admin) && $_admin)
        @vite('resources/scss/app-admin.scss')
    @else
        @vite('resources/scss/app.scss')
    @endif
@else
    @if(isset($_admin) && $_admin)
        @vite('resources/scss/app-admin-dark.scss')
    @else
        @vite('resources/scss/app-dark.scss')
    @endif

@endif
@php
    $c = Auth::check() ? 'resources/css/'. Auth::user()->settings->color .'.css' : false;
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
<link rel="stylesheet" type="text/css" href="{{ asset("vendor/cookie-consent/css/cookie-consent.css") }}">
