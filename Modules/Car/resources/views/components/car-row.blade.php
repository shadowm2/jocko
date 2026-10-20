<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->company?->name) }}
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::popup-image
            :image="$row->images()->first()"
            :alt="$row->name"
            :initial="mb_substr($row->name, 0, 1)"
            :name="$row->name"
        />
    </x-table.cell>
    <x-table.cell>
        <x-car::car-action-cell :$row />
    </x-table.cell>
</x-table.row>
