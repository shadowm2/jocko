@props([
    'title' => null,
    'description' => null,
    'id' => 'table-filter-panel',
    'values' => [],
    'filters' => [],
])

@php
    $title = $title ?? __('strings.Filters');
    $activeFilterCount = collect($values)->filter(function ($value) {
        if (is_array($value)) {
            return count($value) > 0;
        }

        return is_bool($value) ? $value : filled($value);
    })->count();
@endphp

<div x-data="{ filtersOpen: false }" @keydown.escape.window="filtersOpen = false" class="flex flex-wrap items-center gap-2">
    <button
        type="button"
        @click="filtersOpen = true"
        aria-controls="{{ $id }}"
        :aria-expanded="filtersOpen"
        class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:border-accent hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200"
    >
        <span class="flex size-7 items-center justify-center rounded-full bg-accent/10 text-accent">
            <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" class="size-4">
                <path d="M3.5 5h13M5.5 10h9m-6.5 5h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
            </svg>
        </span>
        {{ $title }}
        @if ($activeFilterCount > 0)
            <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-accent px-1.5 py-0.5 text-xs font-semibold text-accent-foreground">
                {{ $activeFilterCount }}
            </span>
        @endif
    </button>

    @foreach ($filters as $filter)
        @php
            $name = $filter['name'];
            $value = data_get($values, $name);
            $isActive = is_array($value) ? count($value) > 0 : (is_bool($value) ? $value : filled($value));
            $label = $filter['label'] ?? $name;

            if (is_array($value)) {
                $valueLabel = collect($value)
                    ->map(fn ($selected) => $filter['options'][$selected] ?? $selected)
                    ->implode(', ');
            } else {
                $valueLabel = ($filter['options'][$value] ?? $value);
            }
        @endphp

        @if ($isActive)
            <span class="inline-flex max-w-64 items-center gap-1 rounded-full border border-accent/30 bg-accent/5 py-1 ps-3 pe-1 text-sm text-zinc-700 dark:text-zinc-200">
                <span class="truncate">{{ $label }}: {{ $valueLabel }}</span>
                <button
                    type="button"
                    wire:click="clearFilter('{{ $name }}')"
                    aria-label="{{ __('strings.Clear Filter') }}: {{ $label }}"
                    class="flex size-7 shrink-0 items-center justify-center rounded-full text-zinc-500 transition hover:bg-accent/10 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent dark:text-zinc-400"
                >
                    <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" class="size-4">
                        <path d="m5 5 10 10M15 5 5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                </button>
            </span>
        @endif
    @endforeach

    <div
        x-cloak
        x-show="filtersOpen"
        x-transition.opacity.duration.200ms
        @click="filtersOpen = false"
        class="fixed inset-0 z-40 bg-zinc-950/40"
        aria-hidden="true"
    ></div>

    <aside
        id="{{ $id }}"
        x-cloak
        x-show="filtersOpen"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-[min(92vw,38rem)] overflow-y-auto border-r border-zinc-200 bg-white p-5 shadow-2xl dark:border-zinc-700 dark:bg-zinc-900 sm:p-7"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $id }}-title"
        @click.stop
    >
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h2 id="{{ $id }}-title" class="text-xl font-semibold text-zinc-900 dark:text-white">
                    {{ $title }}
                </h2>
                @if ($description)
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        {{ $description }}
                    </p>
                @endif
            </div>
            <button
                type="button"
                @click="filtersOpen = false"
                class="flex size-9 items-center justify-center rounded-full text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900 focus:outline-none focus:ring-2 focus:ring-accent dark:hover:bg-zinc-800 dark:hover:text-white"
                aria-label="{{ __('strings.Close') }}"
            >
                <svg aria-hidden="true" viewBox="0 0 20 20" fill="none" class="size-5">
                    <path d="m5 5 10 10M15 5 5 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        {{ $slot }}
    </aside>
</div>
