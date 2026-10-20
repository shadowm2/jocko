<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->units_count) }}
    </x-table.cell>
    <x-table.cell>
        <x-inventory::unit-group-action-cell :$row />
    </x-table.cell>
</x-table.row>
