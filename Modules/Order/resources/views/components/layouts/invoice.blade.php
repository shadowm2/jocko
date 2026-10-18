<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}"
>

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ $title ?? config('app.name') }}</title>
    <link
        rel="stylesheet"
        href="{{ asset('assets/fonts/irs/index.css') }}"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Always light, so it prints dark on white whatever the dashboard theme is. --}}
<body class="min-h-screen bg-white text-zinc-900 antialiased">
    {{ $slot }}
</body>

</html>
