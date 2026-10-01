@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Supplier')"
        :href="route('suppliers.update', ['supplier' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
