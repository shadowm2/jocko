@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('dashboard::strings.Edit Province')"
        :href="route('provinces.update', ['province' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
