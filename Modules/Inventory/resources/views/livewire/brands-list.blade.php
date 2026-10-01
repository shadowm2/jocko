<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Brands List')"
        row-component="inventory::components.brand-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('brands.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Brand Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
