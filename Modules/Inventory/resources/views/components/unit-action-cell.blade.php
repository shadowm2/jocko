@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Unit')"
        :href="route('units.edit', ['unit' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
