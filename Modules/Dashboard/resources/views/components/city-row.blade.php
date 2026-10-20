<x-table.row>
    <x-table.cell>
        {{ $row->name }}
    </x-table.cell>
    <x-table.cell>
        <flux:link :href="route('provinces.edit', ['province' => $row->province])">
            {{ \App\Helpers\Utils::pDigits($row->province?->name) }}
        </flux:link>
    </x-table.cell>
    <x-table.cell>
        <flux:link :href="route('countries.edit', ['country' => $row->province->country])">
            {{ \App\Helpers\Utils::pDigits($row->province?->country?->name) }}
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
            :tooltip="__('dashboard::strings.Edit City')"
            :href="route('cities.edit', ['city' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
