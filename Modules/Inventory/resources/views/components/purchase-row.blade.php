@php
    use App\Helpers\Utils;
@endphp
<x-table.row>
    <x-table.cell>

        <flux:button
            variant="ghost"
            :href="route('suppliers.update', ['supplier' => $row->supplier])"
        >
            {{ $row->supplier->user->fullName() }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        <flux:button
            variant="ghost"
            :href="route('warehouses.update', ['warehouse' => $row->warehouse])"
        >
            {{ $row->warehouse->name }}
        </flux:button>
    </x-table.cell>
    <x-table.cell>
        {{ $row->order_number }}
    </x-table.cell>
    <x-table.cell>
        <flux:badge :color="$row->status->color()">
            {{ $row->status->label() }}
        </flux:badge>
    </x-table.cell>
    <x-table.cell>
        {{ Utils::pDigits($row->ordered_at_jalali?->format('Y/m/d')) }}
    </x-table.cell>
    <x-table.cell>
        {{ Utils::pDigits($row->received_at_jalali?->format('Y/m/d')) }}
    </x-table.cell>
    <x-table.cell>
        <flux:button
            icon="pencil-square"
            variant="ghost"
            :href="route('purchases.update', ['purchase' => $row])"
        />
    </x-table.cell>
</x-table.row>
