<x-table.row>
    <x-table.cell>
        <flux:button
            variant="ghost"
            :href="route('items.update', ['item' => $row->item])"
        >
            {{ $row->item->name }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        <flux:button
            variant="ghost"
            :href="route('brands.update', ['brand' => $row->brand])"
        >
            {{ $row->brand->name }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        {{ $row->sku }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->barcode }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->part_number }}
    </x-table.cell>
    <x-table.cell>
        {{ number_format($row->purchase_price) }}
    </x-table.cell>
    <x-table.cell>
        {{ number_format($row->sale_price) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:click="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('inventory::strings.Edit Item Brand')"
            :href="route('items.brands.update', ['item' => $item, 'itemBrand' => $row])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
