<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() !== 'fa' ? 'ltr' : 'rtl' }}"
>

<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ $title ?? __('errors.Error') }}</title>
    <link
        rel="stylesheet"
        href="{{ asset('assets/fonts/irs/index.css') }}"
    >

    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">

    <div class="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-6">

        {{-- Background --}}
        <div
            class="absolute inset-0 -z-20 bg-gradient-to-br from-white via-zinc-50 to-zinc-100 dark:from-zinc-950 dark:via-zinc-900 dark:to-black">
        </div>

        <div class="absolute -top-32 left-1/2 -z-10 h-96 w-96 -translate-x-1/2 rounded-full bg-indigo-500/15 blur-3xl">
        </div>

        <div class="absolute bottom-0 right-0 -z-10 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>

        <flux:card
            class="relative w-full max-w-xl overflow-hidden border border-zinc-200/70 bg-white/80 shadow-2xl backdrop-blur-xl dark:border-zinc-800 dark:bg-zinc-900/80"
        >

            {{-- Decorative line --}}
            <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-sky-500 to-cyan-400"></div>

            <div class="relative p-10">

                {{-- Giant background number --}}
                @isset($code)
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">

                        <span class="select-none text-9xl font-black tracking-tight text-zinc-100 dark:text-zinc-800">
                            {{ $code }}
                        </span>

                    </div>
                @endisset

                {{-- Content --}}
                <div class="relative space-y-6 text-center">

                    {{ $slot }}

                </div>

            </div>

        </flux:card>

    </div>

</body>

</html>
