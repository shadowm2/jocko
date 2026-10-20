<x-table.row>
    <x-table.cell>
        <div class="flex flex-row items-center gap-3 min-w-75">
            <flux:button
                x-data="warehouseChannel"
                @click="broadcastWarehouseChange(@js($row->slug))"
                variant="ghost"
                class="group p-0! hover:bg-transparent!"
                wire:click="changeActiveWarehouse('{{ $row->slug }}')"
            >
                <flux:icon.check-circle
                    class="{{ $row->id === $activeWarehouse?->id ? 'text-green-600 size-8' : 'text-gray-400 size-6 group-hover:text-green-600/60' }}
                transition-all group-hover:size-8
                hover:text-green-400
                "
                ></flux:icon.check-circle>
            </flux:button>
            {{ $row->name }}
        </div>
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits(\Illuminate\Support\Str::limit((string) $row->description, 15)) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->warehouse_items_count) }}
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('inventory::strings.Edit Warehouse')"
            :href="route('warehouses.edit', ['warehouse' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
