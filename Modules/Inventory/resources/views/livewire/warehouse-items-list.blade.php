<div class="flex flex-col gap-5">
    <livewire:inventory-warehouse-picker />
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Warehouse Items List')"
    >
        @if ($warehouse instanceof \Illuminate\Database\Eloquent\Model)
            <x-slot name="action">
                <flux:button
                    icon:trailing="plus"
                    :href="route('warehouses.items.create', ['warehouse' => $warehouse->slug])"
                    variant="primary"
                >
                    {{ __('inventory::strings.Warehouse Item Add') }}
                </flux:button>
            </x-slot>
        @endif
    </x-table.data-table>
</div>
