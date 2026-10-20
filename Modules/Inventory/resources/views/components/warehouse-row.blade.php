<x-table.row>
    <x-table.cell>
        <x-inventory::warehouse-name-cell :$row :$activeWarehouse />
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\Illuminate\Support\Str::limit((string) $row->description, 15)) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->warehouse_items_count) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-inventory::warehouse-action-cell :$row />
    </x-table.cell>
</x-table.row>
