<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('car::strings.Cars List')"
    >
        <x-slot name="toolbar">
            <x-table.filters :filters="$filterDefinitions" :sort-options="$sortOptions" :sort-by="$sortBy" :sort-direction="$sortDirection" clear-action="clearFilters" />
        </x-slot>

        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('cars.create')"
                variant="primary"
            >
                {{ __('car::strings.Add Car') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
