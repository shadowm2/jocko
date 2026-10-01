<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Brand Name') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Brand Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<x-dashboard::file-upload
    :label="__('inventory::attributes.Brand Logo')"
    accept="image/*"
/>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Brand Description') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Brand Description')"
        wire:model="form.description"
    />
    <flux:error name="form.description" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Is Active') }}
    </flux:label>
    <flux:switch wire:model="form.is_active" />
    <flux:error name="form.is_active" />
</flux:field>
