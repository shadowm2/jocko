@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Warehouse')"
        :href="route('warehouses.edit', ['warehouse' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
