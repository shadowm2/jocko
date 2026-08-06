<x-table.row>
    <x-table.cell>
        {{ $row->user->fullName() }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->userCar->car->name }}
    </x-table.cell>
    <x-table.cell>
        <flux:button
            href="{{ route('orders.edit', ['order' => $row]) }}"
            :tooltip="__('order::strings.Edit Order')"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
