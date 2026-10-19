<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Low Stock List')"
        row-component="inventory::components.low-stock-row"
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
    </x-table.data-table>
</div>
