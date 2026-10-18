@props(['row' => null])
<div>
    <flux:link :href="route('units.groups.edit', ['unitGroup' => $row->unitGroup->slug])">{{ $row->unitGroup->name }}</flux:link>
</div>
