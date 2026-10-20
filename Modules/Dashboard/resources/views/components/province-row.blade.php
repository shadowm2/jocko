<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        <flux:link :href="route('countries.edit', ['country' => $row->country])">
            {{ \App\Helpers\Utils::pDigits($row->country?->name) }}
        </flux:link>
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('dashboard::strings.Edit Province')"
            :href="route('provinces.edit', ['province' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
