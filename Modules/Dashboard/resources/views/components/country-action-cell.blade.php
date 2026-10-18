@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('dashboard::strings.Edit Country')"
        :href="route('countries.edit', ['country' => $row->code])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
