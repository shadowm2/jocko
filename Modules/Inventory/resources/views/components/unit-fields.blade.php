<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Unit Name') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Unit Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Unit Symbol') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Unit Symbol')"
        wire:model="form.symbol"
    />
    <flux:error name="form.symbol" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Unit Group') }}
    </flux:label>
    <flux:select
        :placeholder="__('inventory::strings.Select Unit Group')"
        wire:model="form.unit_group"
    >
        <flux:select.option value="">
            {{ __('inventory::strings.Select Unit Group') }}
        </flux:select.option>
        @foreach ($unitGroups as $unitGroup)
            <flux:select.option :value="$unitGroup->slug">
                {{ $unitGroup->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.unit_group" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Conversion Factor') }}
    </flux:label>
    <flux:input
        type="number"
        step="any"
        :placeholder="__('inventory::strings.Enter Unit Conversion Factor')"
        wire:model="form.conversion_factor"
    />
    <flux:error name="form.conversion_factor" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Is Base') }}
    </flux:label>
    <flux:switch
        :placeholder="__('inventory::strings.Unit Is Active')"
        wire:model="form.is_base"
    />
    <flux:error name="form.is_base" />
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
