@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('dashboard::strings.Edit Country')"
        :href="route('countries.update', ['country' => $row->code])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
