<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->symbol) }}
    </x-table.cell>
    <x-table.cell>
        <x-inventory::unit-unit-group-cell :$row />
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\App\Helpers\Utils::formatQuantity($row->conversion_factor, 3)) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_base"
            wire:change="toggleIsBase('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-inventory::unit-action-cell :$row />
    </x-table.cell>
</x-table.row>
