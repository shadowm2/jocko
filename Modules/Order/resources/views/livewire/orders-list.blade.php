<div>
    <x-table.data-table
        :title="__('order::strings.Orders List')"
        :$rows
        :$columns
        row-component="order::components.order-table-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('orders.create')"
                variant="primary"
            >
                {{ __('order::strings.Submit Order') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
