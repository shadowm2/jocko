@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('dashboard::strings.Edit City')"
        :href="route('cities.update', ['city' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
