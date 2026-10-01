<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Units List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('units.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Unit Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
