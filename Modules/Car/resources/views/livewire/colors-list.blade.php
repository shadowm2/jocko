<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('car::strings.Colors List')"
    >
        <x-slot name="toolbar">
            <x-table.filters :filters="$filterDefinitions" :sort-options="$sortOptions" :sort-by="$sortBy" :sort-direction="$sortDirection" clear-action="clearFilters" />
        </x-slot>

        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('colors.create')"
                variant="primary"
            >
                {{ __('car::strings.Add Color') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
