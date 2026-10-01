@props(['row' => null])
<div>
    <flux:button
        :tooltip="__('dashboard::strings.Edit Category')"
        :href="route('categories.update', ['category' => $row->slug])"
        icon="pencil-square"
        variant="ghost"
    />
</div>
