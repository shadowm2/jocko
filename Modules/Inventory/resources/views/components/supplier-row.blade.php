<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->user?->first_name . ' ' . $row->user?->last_name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->city?->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->phone) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\Illuminate\Support\Str::limit((string) $row->address, 45)) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->website) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <x-inventory::supplier-action-cell :$row />
    </x-table.cell>
</x-table.row>
