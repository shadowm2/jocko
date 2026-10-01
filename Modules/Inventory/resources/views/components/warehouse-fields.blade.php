<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Warehouse Name') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Warehouse Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Warehouse Description') }}
    </flux:label>
    <flux:textarea
        :placeholder="__('inventory::strings.Enter Warehouse Description')"
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
