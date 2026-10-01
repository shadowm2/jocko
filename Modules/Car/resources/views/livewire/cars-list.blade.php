<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('car::strings.Cars List')"
    >
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
