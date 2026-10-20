<x-table.row>
    <x-table.cell>
        <x-dashboard::category-name-cell :$row />
    </x-table.cell>
    <x-table.cell>
        {{ __('dashboard::strings.category_types.' . $row->type->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->parent?->name) }}
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::category-icon-cell :$row />
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::category-action-cell :$row />
    </x-table.cell>
</x-table.row>
