<x-table.row>
    <x-table.cell>
        <div
            class="flex flex-row items-center gap-2"
            style="padding-inline-start: {{ $row->depth * 1.15 }}rem"
        >
            <flux:badge size="sm">
                {{ \App\Helpers\Utils::pDigits($row->depth + 1) }}
            </flux:badge>
            <flux:icon
                name="folder"
                class="size-{{ max(4, 6 - $row->depth) }}"
            />
            {!! $row->name !!}
        </div>
    </x-table.cell>
    <x-table.cell>
        {{ __('dashboard::strings.category_types.' . $row->type->name) }}
    </x-table.cell>
    <x-table.cell>
        @if($row->parent)
            <flux:link :href="route('categories.edit', ['category' => $row->parent])">
                {{ \App\Helpers\Utils::pDigits($row->parent?->name) }}
            </flux:link>
        @else
            {{ \App\Helpers\Utils::pDigits($row->parent?->name) }}
        @endif


    </x-table.cell>
    <x-table.cell>
        <flux:icon name="{{ $row->icon }}" />
    </x-table.cell>
    <x-table.cell>
        <flux:switch
            :checked="$row->is_active"
            wire:change="toggleIsActive('{{ $row->slug }}')"
        />
    </x-table.cell>
    <x-table.cell>
        <flux:button
            :tooltip="__('dashboard::strings.Edit Category')"
            :href="route('categories.edit', ['category' => $row->slug])"
            icon="pencil-square"
            variant="ghost"
        />
    </x-table.cell>
</x-table.row>
