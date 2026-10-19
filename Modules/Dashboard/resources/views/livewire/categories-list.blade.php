<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('dashboard::strings.Categories List')"
    >
        <x-slot name="toolbar">
            <x-table.filter-drawer :values="$filters" :filters="$filterDefinitions">
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
                :href="route('categories.create')"
                variant="primary"
            >
                {{ __('dashboard::strings.Category Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
