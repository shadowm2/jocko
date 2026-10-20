@props([
    'options',
    'model',
    'value' => null,
    'placeholder' => '',
    'multiple' => false,
    'required' => false,
])

@php($invalid = $errors->has($model))

<div data-searchable-select-container @if ($invalid) data-invalid @endif>
    <div wire:ignore>
        <select
            data-searchable-select
            data-model="{{ $model }}"
            data-placeholder="{{ $placeholder }}"
            @if ($required) required @endif
            @if ($multiple) multiple @endif
        >
            @unless ($multiple)
                <option value="" @selected(blank($value))></option>
            @endunless

            @foreach ($options as $optionValue => $optionLabel)
                <option
                    value="{{ $optionValue }}"
                    @selected($multiple
                        ? in_array((string) $optionValue, array_map('strval', (array) $value), true)
                        : (string) $value === (string) $optionValue)
                >{{ $optionLabel }}</option>
            @endforeach
        </select>
    </div>
</div>
