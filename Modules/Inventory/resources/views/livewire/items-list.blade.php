<div x-data="{
    selectedBrand: null
}">
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Items List')"
        row-component="inventory::components.item-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('items.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Item Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
