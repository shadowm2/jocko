<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Warehouses List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('warehouses.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Warehouse Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
