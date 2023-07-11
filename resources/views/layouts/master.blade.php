<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Primary Meta Tags -->
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="{{ config('app.name') }}">
    <meta name="author" content="vACC Germany">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="https://vatsim-germany.org">
    <meta name="lang" content="{{ app()->getLocale() }}">

    <!-- favicon -->
    <link rel="shortcut icon" href="/favicon.ico">

    @vite('resources/scss/app.scss')

    @stack('styles')
</head>

<body>
@include('layouts.header')

@yield('content')

@include('layouts.footer')

@vite(['resources/ts/app.ts'])

@stack('scripts')

</body>

</html>
