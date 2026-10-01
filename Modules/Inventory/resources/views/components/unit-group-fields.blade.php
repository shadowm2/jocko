<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Unit Group Name') }}
    </flux:label>

    <flux:input
        :placeholder="__('inventory::strings.Enter Unit Group Name')"
        wire:model="form.name"
    />

    <flux:error name="form.name" />
</flux:field>
