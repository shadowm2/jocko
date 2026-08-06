@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('car::strings.Edit Car Company')"
        :href="route('companies.edit', ['company' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
