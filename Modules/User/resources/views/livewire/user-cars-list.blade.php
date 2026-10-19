<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('user::strings.User :name Cars', [
            'name' => $this->user->first_name . ' ' . $this->user->last_name,
        ])"
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
    </x-table.data-table>
</div>
