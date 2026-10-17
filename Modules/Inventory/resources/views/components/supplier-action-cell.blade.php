@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Supplier')"
        :href="route('suppliers.edit', ['supplier' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
