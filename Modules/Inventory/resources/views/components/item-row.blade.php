<x-table.row>
    <x-table.cell>
        <x-dashboard::popup-image
            :image="$row->images->first()"
            :alt="$row->name"
            :name="$row->name"
            :initial="mb_strtoupper(mb_substr($row->name, 0, 3))"
        />
    </x-table.cell>
    <x-table.cell>
        {{ $row->name }}
    </x-table.cell>
    <x-table.cell>
        <flux:button
            variant="ghost"
            :href="route('categories.update', ['category' => $row->category])"
        >
            {{ $row->category->name }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        <flux:button
            variant="ghost"
            :href="route('units.groups.update', ['unitGroup' => $row->unitGroup])"
        >
            {{ $row->unitGroup->name }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->brands_count) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:click="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('inventory::strings.Edit Item')"
            :href="route('items.update', ['item' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
        <flux:button
            :tooltip="__('inventory::strings.Item Brands')"
            :href="route('items.brands.index', ['item' => $row->slug])"
            icon="tag"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
