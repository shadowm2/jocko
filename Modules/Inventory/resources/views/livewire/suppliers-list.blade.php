<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Suppliers List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('suppliers.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Supplier Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
