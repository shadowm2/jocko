<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Name') }}
    </flux:label>
    <flux:input
        required
        :placeholder="__('inventory::strings.Enter Item Name')"
        wire:model="form.name"
    />
    <flux:error name="form.name" />
</flux:field>

<x-dashboard::category-picker
    label="{{ __('dashboard::attributes.Category Parent') }}"
    placeholder="{{ __('dashboard::strings.Select Category Parent') }}"
    wire:model="form.category"
    :categories="$categories"
/>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Unit Group') }}
    </flux:label>
    <flux:select
        required
        :placeholder="__('inventory::strings.Select Item Unit Group')"
        wire:model="form.unit_group"
    >
        @foreach ($unitGroups as $unitGroup)
            <flux:select.option :value="$unitGroup->slug">
                {{ $unitGroup->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.unit_group" />
</flux:field>

<x-dashboard::file-upload
    :label="__('inventory::attributes.Item Images')"
    accept="image/*"
    multiple
/>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Description') }}
    </flux:label>
    <flux:input
        :placeholder="__('inventory::strings.Enter Item Description')"
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
