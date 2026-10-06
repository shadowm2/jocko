@props([
    'label' => null,
    'error' => null,
    'precision' => 18,
    'scale' => 4,
])

@php
    $integerDigits = $precision - $scale;
    $maxValue = str_repeat('9', $integerDigits) . ($scale > 0 ? '.' . str_repeat('9', $scale) : '');
@endphp

<flux:field>
    @if ($label)
        <flux:label>{{ $label }}</flux:label>
    @endif

    <flux:input
        type="text"
        inputmode="decimal"
        dir="ltr"
        max="{{ $maxValue }}"
        maxlength="{{ $integerDigits + ($scale > 0 ? $scale + 1 : 0) }}"
        data-max-integer-digits="{{ $integerDigits }}"
        data-max-decimal-places="{{ $scale }}"
        x-data="localizedDigits"
        @input="sanitize($event)"
        {{ $attributes }}
    />

    @if ($error)
        <flux:error :name="$error" />
    @endif
</flux:field>
