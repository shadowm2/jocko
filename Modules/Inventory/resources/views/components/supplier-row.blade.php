<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->user?->first_name . ' ' . $row->user?->last_name) }}
    </x-table.cell>
    <x-table.cell>
        @if ($row->city)
            <flux:link :href="route('cities.edit', ['city' => $row->city])">
                {{ \App\Helpers\Utils::pDigits($row->city->name) }}
            </flux:link>
        @endif
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->phone) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\Illuminate\Support\Str::limit((string) $row->address, 45)) }}
    </x-table.cell>
    <x-table.cell>
        @if(\Illuminate\Support\Str::isUrl($row->website))
            <flux:link :href="$row->website">
                {{ \App\Helpers\Utils::pDigits($row->website) }}
            </flux:link>
        @else
            {{ \App\Helpers\Utils::pDigits($row->website) }}
        @endif

    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('inventory::strings.Edit Supplier')"
            :href="route('suppliers.edit', ['supplier' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
