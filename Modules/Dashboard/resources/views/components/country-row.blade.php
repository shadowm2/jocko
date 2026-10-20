<x-table.row>
    <x-table.cell>
        <x-dashboard::country-name-cell :$row />
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->code) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->code3) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->code }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::country-action-cell :$row />
    </x-table.cell>
</x-table.row>
