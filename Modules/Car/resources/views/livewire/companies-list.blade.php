<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('car::strings.Car Companies List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('companies.create')"
                variant="primary"
            >
                {{ __('car::strings.Add Car Company') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
