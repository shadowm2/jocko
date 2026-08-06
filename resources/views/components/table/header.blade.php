@props([
    'column' => null,
])

<flux:table.column>

    @if ($column)

        <button
            wire:click="sort('{{ $column }}')"
            class="flex items-center gap-2 font-medium"
        >

            {{ $slot }}

            @if ($sortBy === $column)

                @if ($sortDirection === 'asc')
                    <flux:icon.chevron-up class="size-4" />
                @else
                    <flux:icon.chevron-down class="size-4" />
                @endif
            @else
                <flux:icon.chevron-up-down class="size-4 opacity-40" />

            @endif

        </button>
    @else
        {{ $slot }}

    @endif

</flux:table.column>
