@props(['row' => null])
<div>
    <flux:button
        :href="route('units.groups.update', ['unitGroup' => $row->unitGroup->slug])"
        variant="ghost"
    >
        {{ $row->unitGroup->name }}
    </flux:button>
</div>
