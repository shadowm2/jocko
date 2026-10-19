<div>
    <x-table.data-table
        :title="__('order::strings.Payments List')"
        :$rows
        :$columns
        row-component="order::components.payment-table-row"
    >
        <x-slot name="toolbar">
            <x-table.filter-drawer :values="$filters" :filters="$filterDefinitions">
                <x-table.filters
                    :filters="$filterDefinitions"
                    clear-action="clearFilters"
                    panel
                />
            </x-table.filter-drawer>
        </x-slot>

        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('payments.create')"
                variant="primary"
            >
                {{ __('order::strings.Submit Payment') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
