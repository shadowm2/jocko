@props([
    'model' => null,
    'placeholder' => null,
])

@php
    $model = $model ?? $attributes->wire('model')->value();
    $placeholder = $placeholder ?? __('inventory::strings.Select Warehouse Item');
@endphp

<div
    x-data="{
        open: false,
        search: '',
        selected: @entangle('value'),
        items: @entangle('items'),
        itemIndex: {},

        init() {
            this.rebuildIndex();
            this.pruneSelection();

            this.$watch('items', () => {
                this.rebuildIndex();
                this.pruneSelection();
            });
        },

        rebuildIndex() {
            this.itemIndex = Object.fromEntries(
                this.items.map(item => [item.slug, item])
            );
        },

        /**
         * Drop selected slugs that no longer exist in the loaded
         * warehouse, e.g. after switching warehouses.
         *
         * Only writes back when the value actually changes: assigning a
         * new array unconditionally re-triggers reactive effects, which
         * previously produced an infinite loop.
         */
        pruneSelection() {
            if (!Array.isArray(this.selected)) {
                if (this.selected === null || this.selected === undefined) {
                    this.selected = [];
                }
                return;
            }

            const pruned = this.selected.filter(slug => slug in this.itemIndex);

            if (pruned.length !== this.selected.length) {
                this.selected = pruned;
            }
        },

        get selectedItems() {
            if (!Array.isArray(this.selected)) {
                return [];
            }

            return this.selected
                .map(slug => this.itemIndex[slug] ?? null)
                .filter(Boolean);
        },

        isSelected(slug) {
            return Array.isArray(this.selected) && this.selected.includes(slug);
        },

        toggleItem(slug) {
            if (!Array.isArray(this.selected)) {
                this.selected = [];
            }

            this.selected = this.isSelected(slug)
                ? this.selected.filter(s => s !== slug)
                : [...this.selected, slug];
        },

        clearSelection() {
            this.selected = [];
            this.open = false;
        },

        get filteredItems() {
            const search = this.search
                .toLowerCase()
                .replaceAll(' ', '');

            if (!search) {
                return this.items;
            }

            return this.items.filter(item =>
                item.item.name
                .toLowerCase()
                .replaceAll(' ', '')
                .includes(search)
            );
        },

        close() {
            this.open = false;
        },
    }"
    @click.outside="close()"
    class="relative"
>
    <flux:field>
        <flux:label>
            {{ $attributes->get('label') ?? __('inventory::attributes.Warehouse Item') }}
        </flux:label>

        {{-- Trigger --}}
        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between gap-2 rounded-lg border border-zinc-200 bg-white px-3 py-2.5 text-sm shadow-xs transition hover:bg-zinc-50 dark:border-white/10 dark:bg-white/10 dark:hover:bg-white/15"
        >
            <span
                class="flex min-w-0 flex-1 flex-wrap items-center gap-1"
                :class="selectedItems.length
                    ?
                    'text-zinc-900 dark:text-white' :
                    'text-zinc-400'"
            >
                <span x-show="selectedItems.length === 0">
                    {{ $placeholder }}
                </span>

                <template
                    x-for="item in selectedItems"
                    :key="item.slug"
                >
                    <span
                        class="inline-flex max-w-full items-center gap-1 rounded-md bg-zinc-100 px-2 py-0.5 text-xs dark:bg-white/10"
                    >
                        <span
                            class="truncate"
                            x-text="item.item.name"
                        ></span>

                        <span class="text-zinc-400">
                            (<span x-text="item.quantity"></span>)
                        </span>
                    </span>
                </template>
            </span>

            <span
                x-show="selectedItems.length > 0"
                x-cloak
                @click.stop="clearSelection()"
                class="shrink-0 text-zinc-400 transition hover:text-zinc-600 dark:hover:text-zinc-200"
            >
                <flux:icon.x-mark class="size-4" />
            </span>

            <flux:icon.chevron-down
                class="size-4 shrink-0 text-zinc-400 transition"
                ::class="{ 'rotate-180': open }"
            />
        </button>

        {{-- Dropdown --}}
        <div
            x-show="open"
            x-cloak
            x-transition.origin.top
            class="absolute z-50 mt-1 w-full overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xl dark:border-white/10 dark:bg-zinc-900"
        >
            {{-- Search --}}
            <div class="border-b border-zinc-100 p-2 dark:border-white/10">
                <flux:input
                    x-model.debounce.150ms="search"
                    placeholder="{{ __('inventory::strings.Search') }}..."
                    icon="magnifying-glass"
                    autocomplete="off"
                />
            </div>

            {{-- Options --}}
            <div class="max-h-72 overflow-y-auto p-1">
                <button
                    type="button"
                    x-show="selectedItems.length > 0"
                    @click="clearSelection()"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-white/10"
                >
                    <flux:icon.x-mark class="size-4 text-zinc-400" />

                    <span class="text-zinc-400">
                        {{ __('inventory::strings.Clear Selection') }}
                    </span>
                </button>

                <template
                    x-for="item in filteredItems"
                    :key="item.slug"
                >
                    <button
                        type="button"
                        @click="toggleItem(item.slug)"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-white/10"
                        :class="{
                            'bg-zinc-100 dark:bg-white/10': isSelected(item.slug)
                        }"
                    >
                        <span
                            class="flex size-4 shrink-0 items-center justify-center rounded border"
                            :class="isSelected(item.slug)
                                ?
                                'border-zinc-900 bg-zinc-900 dark:border-white dark:bg-white' :
                                'border-zinc-300 dark:border-white/20'"
                        >
                            <flux:icon.check
                                x-show="isSelected(item.slug)"
                                class="size-3 text-white dark:text-zinc-900"
                            />
                        </span>

                        <span
                            class="truncate"
                            x-text="item.item.name"
                        ></span>

                        <span
                            class="ms-auto text-xs text-zinc-400"
                            x-text="item.quantity"
                        ></span>
                    </button>
                </template>

                <div
                    x-show="filteredItems.length === 0"
                    x-cloak
                    class="px-3 py-8 text-center text-sm text-zinc-400"
                >
                    {{ __('inventory::strings.No items found.') }}
                </div>
            </div>
        </div>

        <flux:error :name="$model" />
    </flux:field>
</div>