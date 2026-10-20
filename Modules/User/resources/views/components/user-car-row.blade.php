<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->car?->company?->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->car?->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->color?->name) }}
    </x-table.cell>
    <x-table.cell>
        <x-user::user-car-actions-cell :$row />
    </x-table.cell>
</x-table.row>
