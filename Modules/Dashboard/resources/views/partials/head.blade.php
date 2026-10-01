<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
/>

<title>
    {{ filled($title ?? null) ? $title : config('app.name', 'Laravel') }}
</title>

<link
    rel="icon"
    href="/favicon.ico"
    sizes="any"
>
<link
    rel="icon"
    href="/favicon.svg"
    type="image/svg+xml"
>
<link
    rel="apple-touch-icon"
    href="/apple-touch-icon.png"
>
<link
    href="{{ asset('assets/fonts/irs/index.css') }}"
    rel="stylesheet"
>
<link
    rel="stylesheet"
    href="{{ asset('vendor/persian-datepicker/dist/css/persian-datepicker.min.css') }}"
>

<script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/persian-date/dist/persian-date.min.js') }}"></script>
<script src="{{ asset('vendor/persian-datepicker/dist/js/persian-datepicker.js') }}"></script>

@fluxAppearance
@vite(['resources/css/app.css', 'resources/js/app.js', 'Modules/Inventory/resources/assets/js/app.js', 'Modules/Dashboard/resources/assets/js/app.js'])
