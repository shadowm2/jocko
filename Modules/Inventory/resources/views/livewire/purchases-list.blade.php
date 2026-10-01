<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Purchases List')"
        row-component="inventory::components.purchase-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('purchases.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Purchase Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
