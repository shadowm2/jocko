@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('car::strings.Edit Car')"
        :href="route('cars.edit', ['car' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
