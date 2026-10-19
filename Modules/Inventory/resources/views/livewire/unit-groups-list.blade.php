<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Unit Groups List')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('units.groups.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Unit Group Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
