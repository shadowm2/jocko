<x-table.row>
    <x-table.cell>
        <x-dashboard::country-name-cell :$row />
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->province?->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->province?->country?->name) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::city-action-cell :$row />
    </x-table.cell>
</x-table.row>
