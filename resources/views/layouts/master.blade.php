<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ (!auth()->check() || !Auth::user()->settings->dark_mode) ? 'light' : 'dark' }}">
<head>
    @include('layouts.head')
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col">
@include('layouts.header')

<main class="public-main flex-1">
    @yield('content')
    {{ $slot ?? '' }}
</main>

@include('layouts.footer')

@vite(['resources/ts/app-public.ts'])
@livewireScriptConfig
@include('layouts.noty')
@stack('scripts')
</body>
</html>
