<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('dashboard::strings.Cities List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('cities.create')"
                variant="primary"
            >
                {{ __('dashboard::strings.City Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
