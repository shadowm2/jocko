<form x-data="warehouseChannel">
    <flux:field>
        <flux:label>
            {{ __('inventory::attributes.Selected Warehouse') }}
        </flux:label>
        <flux:select
            wire:model.live="selectedWarehouse"
            @input="event => broadcastWarehouseChange(event.currentTarget.value)"
        >
            @foreach ($warehouses as $warehouse)
                <flux:select.option value="{{ $warehouse->slug }}">
                    {{ $warehouse->name }}
                </flux:select.option>
            @endforeach
        </flux:select>
    </flux:field>
</form>
