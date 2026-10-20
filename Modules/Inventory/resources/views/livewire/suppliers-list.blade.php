<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Suppliers List')"
        row-component="inventory::components.supplier-row"
    >
        <x-slot name="toolbar">
            <x-table.filter-drawer
                id="supplier-filters-panel"
                :values="$filters"
                :filters="$filterDefinitions"
                :title="__('strings.Filters')"
                :description="__('inventory::strings.Suppliers List')"
            >
                <x-table.filters
                    :filters="$filterDefinitions"
                    :sort-options="$sortOptions"
                    :sort-by="$sortBy"
                    :sort-direction="$sortDirection"
                    clear-action="clearFilters"
                    panel
                />
            </x-table.filter-drawer>
        </x-slot>

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
