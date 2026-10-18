@php
    use App\Helpers\Utils;
@endphp
<x-table.row>
    <x-table.cell>
        {{ $row->warehouse->name }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->item->name }}
    </x-table.cell>
    <x-table.cell>
        <span class="text-red-600 dark:text-red-400">{{ Utils::pDigits(Utils::formatQuantity($row->quantity, 3, ',')) }}</span>
    </x-table.cell>
    <x-table.cell>
        {{ Utils::pDigits(Utils::formatQuantity($row->min_quantity, 3, ',')) }}
    </x-table.cell>
    <x-table.cell>
        {{ Utils::pDigits(Utils::formatQuantity($row->min_quantity - $row->quantity, 3, ',')) }}
    </x-table.cell>
    <x-table.cell>
        <x-inventory::warehouse-item-action-cell :$row />
    </x-table.cell>
</x-table.row>
