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
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
<meta name="apple-mobile-web-app-title" content="VATGER" />

@if(app()->isProduction())
    <script defer src="https://analytics.vatsim-germany.org/script.js" data-website-id="ebee2a79-7a84-4680-af45-7ef23c7d94c2"></script>
@endif

@if(isset($_admin) && $_admin)
    @vite('resources/scss/app-admin.scss')
@else
    @vite('resources/css/app-public.css')
@endif
@if(!isset($_admin) || !$_admin)
    <script>
        (() => {
            let theme = null;
            try {
                theme = window.localStorage?.getItem('vatger-theme');
            } catch {
                // Storage can be unavailable in privacy modes.
            }
            if (theme === 'light' || theme === 'dark') {
                document.documentElement.dataset.theme = theme;
                document.querySelector('meta[name="color-scheme"]')?.setAttribute('content', theme);
            }
        })();
    </script>
@endif
