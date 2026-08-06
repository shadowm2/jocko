@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('car::strings.Edit Color')"
        :href="route('colors.edit', ['color' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
