<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->slug) }}
    </x-table.cell>
    <x-table.cell>
        <x-dashboard::popup-image
            :image="$row->logo"
            :alt="$row->name"
            :initial="mb_substr($row->name, 0, 1)"
            :name="$row->name"
        />
    </x-table.cell>
    <x-table.cell>
        <x-car::company-action-cell :$row />
    </x-table.cell>
</x-table.row>
