@props([
    'icons' => \Modules\Dashboard\Enums\CategoryIcon::values(),
    'placeholder' => 'Select an icon',
    'searchPlaceholder' => __('dashboard::strings.Search Icons'),
    'required' => false,
])

@php
    foreach ($icons as $key => $icon) {
        $icons[$icon] = $icon;
        unset($icons[$key]);
    }
    $model = $attributes->wire('model')->value();
@endphp

<div
    x-data="{
        open: false,
        search: '',
        close() {
            this.open = false;
            this.search = '';
        }
    }"
    @click.outside="close()"
    @keydown.escape.window="close()"
    class="relative"
>
    <flux:field>
        <flux:input
            type="hidden"
            wire:model="{{ $model }}"
            {{ $required ? 'required' : '' }}
        />
        <flux:label>
            {{ $attributes->get('label') }}
        </flux:label>
        <div class="flex flex-row items-stretch">
            @php
                $selectedIcon = $icons[data_get($this, $model)] ?? null;
            @endphp

            @if (!empty($selectedIcon))
                <flux:button
                    size="xs"
                    @click.stop="$wire.set('{{ $model }}', null);open=false"
                    class="rounded-md rounded-l-none aspect-square h-full py-8"
                >
                    <flux:icon.x-mark class="size-4" />
                </flux:button>
            @endif
            {{-- Trigger --}}
            <button
                type="button"
                @click="open = !open"
                class="flex {{ $selectedIcon ? 'rounded-r-none' : '' }} items-center gap-3 rounded-lg border border-zinc-200 bg-white px-3 py-2.5 text-left text-sm shadow-xs transition hover:border-zinc-300 focus:outline-none focus:ring-2 focus:ring-accent/20 dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600"
            >
                @if ($model)
                    @if ($selectedIcon)
                        {{ __('dashboard::strings.Selected Icon:') }}
                        <div
                            class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                            <flux:icon
                                :name="$selectedIcon"
                                class="size-5 text-zinc-600 dark:text-zinc-300"
                            />
                        </div>
                    @else
                        {{ __('dashboard::strings.Icon Selector') }}
                    @endif
                @else
                    {{ __('dashboard::strings.Icon Selector') }}
                    <div
                        class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                        <flux:icon.sparkles class="size-5 text-zinc-400" />
                    </div>
                    <span class="flex-1 text-zinc-500">
                        {{ $placeholder }}
                    </span>
                    <flux:icon.chevron-down class="size-4 text-zinc-400" />
                @endif
            </button>
        </div>
        {{-- Dropdown --}}
        <div
            x-show="open"
            x-transition
            class="absolute z-50 mt-2 w-full overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xl dark:border-zinc-700 dark:bg-zinc-900"
            style="display: none;"
        >
            {{-- Search --}}
            <div class="border-b border-zinc-200 p-2 dark:border-zinc-700">
                <div class="relative">
                    <flux:icon.magnifying-glass class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-zinc-400" />
                    <input
                        x-model="search"
                        type="text"
                        :placeholder="@js($searchPlaceholder)"
                        class="w-full rounded-lg border-0 bg-zinc-100 py-2 pl-9 pr-3 text-sm outline-none focus:ring-2 focus:ring-accent/20 dark:bg-zinc-800"
                    >
                </div>
            </div>
            {{-- Icons --}}
            <div class="max-h-80 overflow-y-auto p-2">
                <div class="grid grid-cols-6 gap-1 sm:grid-cols-[repeat(20,minmax(0,1fr))]">
                    @foreach ($icons as $name => $icon)
                        <button
                            type="button"
                            x-show="
                                search === '' ||
                                @js(strtolower($name)).includes(search.toLowerCase())
                            "
                            title="{{ $name }}"
                            @click="
                                $wire.set(@js($model), @js($name));
                                close();
                            "
                            class="group relative flex aspect-square items-center justify-center rounded-lg text-zinc-500 transition hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100"
                            :class="{
                                'bg-accent/10 text-accent dark:bg-accent/20': @js($model) ===
                                    @js($name)
                            }"
                        >
                            <flux:icon
                                :name="$icon"
                                class="size-5"
                            />
                            {{-- Tooltip --}}
                            <span
                                class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 whitespace-nowrap rounded-md bg-zinc-900 px-2 py-1 text-xs text-white opacity-0 shadow-lg transition group-hover:opacity-100"
                            >
                                {{ $name }}
                            </span>
                        </button>
                    @endforeach
                </div>
                {{-- Empty --}}
                <div
                    x-show="search !== '' && !$el.previousElementSibling.querySelector('button:not([style*=\'display: none\'])')"
                    class="px-3 py-8 text-center text-sm text-zinc-500"
                >
                    {{ __('dashboard::strings.Category Icon Not Found') }}
                </div>
            </div>
        </div>
        <flux:error :name="$model" />
    </flux:field>
</div>
