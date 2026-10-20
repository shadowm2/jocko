<x-table.row>
    <x-table.cell>
        {{ \App\Helpers\Utils::pDigits($row->name) }}
    </x-table.cell>
    <x-table.cell>
        <div class="flex flex-row gap-4 items-center">
            <span>
                {{ mb_strtoupper($row->hex) }}
            </span>

            <span class="rounded-full border-2 border-zinc-600">
                <span
                    class="flex size-6 rounded-full"
                    style="background-color: {{ $row->hex }}; border: 2px solid {{ \App\Helpers\Utils::darken($row->hex) }}"
                ></span>
            </span>
        </div>
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('car::strings.Edit Color')"
            :href="route('colors.edit', ['color' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
