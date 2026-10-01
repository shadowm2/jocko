@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Warehouse Item')"
        :href="route('warehouses.items.update', ['warehouse' => $row->warehouse, 'warehouseItem' => $row])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
