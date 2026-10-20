<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->symbol) }}
    </x-table.cell>
    <x-table.cell>
        <flux:link :href="route('units.groups.edit', ['unitGroup' => $row->unitGroup->slug])">{{ $row->unitGroup->name }}</flux:link>
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
        <flux:button
            :tooltip="__('inventory::strings.Edit Unit')"
            :href="route('units.edit', ['unit' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
