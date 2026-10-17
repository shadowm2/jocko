@php
    use App\Helpers\Utils;
@endphp
<x-table.row>
    <x-table.cell>
        {{ $row->order_number }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->user->fullName() }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->userCar->car->name }}
    </x-table.cell>
    <x-table.cell>
        {{ $row->warehouse->name }}
    </x-table.cell>
    <x-table.cell>
        <div class="flex flex-col">
            <span>{{ Utils::pDigits((string) $row->items_count) }} {{ __('order::strings.Order Items') }}</span>
            <span class="text-xs text-zinc-500">{{ Utils::formatQuantity($row->items_sum_total ?? 0, 2, ',') }} {{ __('order::attributes.Total') }}</span>
        </div>
    </x-table.cell>
    <x-table.cell>
        <flux:badge :color="$row->status->color()">
            {{ $row->status->label() }}
        </flux:badge>
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
