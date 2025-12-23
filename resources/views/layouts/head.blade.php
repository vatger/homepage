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
<link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
<meta name="apple-mobile-web-app-title" content="VATGER" />
<link rel="manifest" href="{{ asset('site.webmanifest') }}" />

<!--
@if(app()->isProduction())
    <script defer src="https://analytics.vatsim-germany.org/script.js" data-website-id="27e4ac08-daf1-4bb8-b07a-09d3e6d6a3d1"></script>
@endif
-->

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
