<x-table.row>
    <x-table.cell>
        <flux:tooltip position="left">
            <flux:button
                variant="ghost"
                :href="route('items.edit', ['item' => $row->item])"
            >
                {{ $row->item->name }}
            </flux:button>
            <flux:tooltip.content class="text-sm! text-center">
                <img
                    src="{{ $row->item->images()->first()?->publicPath() }}"
                    class="h-[100px] rounded-md mb-2"
                />
                @lang('inventory::attributes.Unit Group Name')
                :
                <span class="font-bold">
                    {{ implode(' - ', [$row->item->unitGroup->name]) }}
                </span>
            </flux:tooltip.content>

        </flux:tooltip>
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\App\Helpers\Utils::formatQuantity($row->quantity, 3)) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\App\Helpers\Utils::formatQuantity($row->min_quantity, 3)) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\App\Helpers\Utils::formatQuantity($row->max_quantity, 3)) }}
    </x-table.cell>
    <x-table.cell>
        @if ($row->isLowStock())
            <flux:badge
                color="red"
                icon="exclamation-triangle"
                size="sm"
            >{{ __('inventory::strings.Low Stock') }}</flux:badge>
        @endif
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('inventory::strings.Edit Warehouse Item')"
            :href="route('warehouses.items.edit', ['warehouse' => $row->warehouse, 'warehouseItem' => $row])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
