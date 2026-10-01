@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('inventory::strings.Edit Unit Group')"
        :href="route('units.groups.update', ['unitGroup' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
