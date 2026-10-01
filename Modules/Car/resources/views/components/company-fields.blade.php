<flux:field>
    <flux:label>
        {{ __('car::attributes.Car Company Name') }}
    </flux:label>

    <flux:input
        :placeholder="__('car::strings.Enter Car Company Name')"
        wire:model.live="form.name"
    />

    <flux:error name="form.name" />
</flux:field>

<x-dashboard::file-upload
    :label="__('car::attributes.Car Company Logo')"
    accept="image/*"
/>
