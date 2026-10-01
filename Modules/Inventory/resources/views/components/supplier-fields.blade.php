<x-dashboard::city-picker
    :$countries
    :$provinces
    :$cities
/>
<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Phone') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Supplier Phone')"
        wire:model="form.phone"
    />
    <flux:error name="form.phone" />
</flux:field>
<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Address') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Supplier Address')"
        wire:model="form.address"
    />
    <flux:error name="form.address" />
</flux:field>
<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Website') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Supplier Website')"
        wire:model="form.website"
    />
    <flux:error name="form.website" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Tax Number') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Supplier Tax Number')"
        wire:model="form.tax_number"
    />
    <flux:error name="form.tax_number" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Postal Code') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Supplier Postal Code')"
        wire:model="form.postal_code"
    />
    <flux:error name="form.postal_code" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Supplier Notes') }}
    </flux:label>
    <flux:textarea
        :placeholder="__('inventory::strings.Enter Supplier Notes')"
        wire:model="form.notes"
    />
    <flux:error name="form.notes" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Is Active') }}
    </flux:label>
    <flux:switch
        :placeholder="__('inventory::strings.Unit Is Active')"
        wire:model="form.is_active"
    />
    <flux:error name="form.is_active" />
</flux:field>
