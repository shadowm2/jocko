<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        <x-car::color-circle :$row />
    </x-table.cell>
    <x-table.cell>
        <x-car::color-action-cell :$row />
    </x-table.cell>
</x-table.row>
