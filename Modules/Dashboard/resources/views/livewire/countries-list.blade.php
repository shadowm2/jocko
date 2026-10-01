<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('dashboard::strings.Countries List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('countries.create')"
                variant="primary"
            >
                {{ __('dashboard::strings.Country Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
