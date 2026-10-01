<flux:field>
    <flux:label>
        {{ __('car::attributes.Color Name') }}
    </flux:label>

    <flux:input
        :placeholder="__('car::strings.Enter color name')"
        wire:model="form.name"
    />

    <flux:error name="form.name" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('car::attributes.Color Code') }}
    </flux:label>

    <flux:input
        type="color"
        wire:model="form.hex"
    />

    <flux:error name="form.hex" />
</flux:field>
