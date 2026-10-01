<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand Brand') }}
    </flux:label>

    <flux:select
        required
        :placeholder="__('inventory::strings.Select Item Brand Brand')"
        wire:model="form.brand"
    >
        @foreach ($brands as $brand)
            <flux:select.option :value="$brand->slug">
                {{ $brand->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.brand" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand SKU') }}
    </flux:label>
    <flux:input
        required
        :placeholder="__('inventory::strings.Enter Item Brand SKU')"
        wire:model="form.sku"
    />
    <flux:error name="form.sku" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand Barcode') }}
    </flux:label>
    <flux:input
        required
        :placeholder="__('inventory::strings.Enter Item Brand Barcode')"
        wire:model="form.barcode"
    />
    <flux:error name="form.barcode" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand Part Number') }}
    </flux:label>
    <flux:input
        required
        :placeholder="__('inventory::strings.Enter Item Brand Part Number')"
        wire:model="form.part_number"
    />
    <flux:error name="form.part_number" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand Purchase Price') }}
    </flux:label>
    <flux:input
        required
        type="number"
        :placeholder="__('inventory::strings.Enter Item Brand Purchase Price')"
        wire:model="form.purchase_price"
    />
    <flux:error name="form.purchase_price" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Item Brand Sale Price') }}
    </flux:label>
    <flux:input
        type="number"
        required
        :placeholder="__('inventory::strings.Enter Item Brand Sale Price')"
        wire:model="form.sale_price"
    />
    <flux:error name="form.sale_price" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Is Active') }}
    </flux:label>
    <flux:switch wire:model="form.is_active" />
    <flux:error name="form.is_active" />
</flux:field>
