@props(['row' => null, 'activeWarehouse'])

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
