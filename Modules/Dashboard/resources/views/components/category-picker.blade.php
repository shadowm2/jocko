@props([
    'categories' => collect(),
    'placeholder' => 'Select category',
])

@php
    $model = $attributes->wire('model')->value();
@endphp

<div
    x-data="{
        open: false,
        search: '',


        get value() {
            return $wire.get('{{ $model }}');
        },


        filteredCategories() {
            const search = this.search
                .toLowerCase()
                .replaceAll(' ', '');


            const matchingCats = this.categories.filter(category =>
                category.name
                .toLowerCase()
                .replaceAll(' ', '')
                .includes(search)
            );


            const parentIds = matchingCats.flatMap(
                category => category.parents
            );


            const validIds = [
                ...new Set([
                    ...parentIds,
                    ...matchingCats.map(category => category.id),
                ])
            ];


            return this.categories.filter(
                category => validIds.includes(category.id)
            );
        },


        categories: @js(
    $categories
        ->map(
            fn($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'depth' => $category->depth,
                'parents' => $category->getParentsRecursive()->pluck('id')->toArray(),
            ],
        )
        ->values(),
),
    }"
    @click.outside="open = false"
    class="relative"
>
    <flux:field>

        <flux:label>
            {{ $attributes->get('label') }}
        </flux:label>

        {{-- Trigger --}}
        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between rounded-lg border border-zinc-200 bg-white px-3 py-2.5 text-sm shadow-xs transition hover:bg-zinc-50 dark:border-white/10 dark:bg-white/10 dark:hover:bg-white/15"
        >
            <span
                class="truncate"
                :class="value
                    ?
                    'text-zinc-900 dark:text-white' :
                    'text-zinc-400'"
            >
                <template x-if="!value">
                    <span>{{ $placeholder }}</span>
                </template>

                <template x-if="value">
                    <span
                        x-text="
                            categories.find(
                                category => category.slug == value
                            )?.name ?? '{{ $placeholder }}'
                        "
                    ></span>
                </template>
            </span>

            <flux:icon.chevron-down
                class="size-4 shrink-0 text-zinc-400 transition"
                ::class="open ? 'rotate-180' : ''"
            />
        </button>

        {{-- Dropdown --}}
        <div
            x-show="open"
            x-transition.origin.top
            class="absolute z-50 mt-1 w-full overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xl dark:border-white/10 dark:bg-zinc-900"
        >
            {{-- Search --}}
            <div class="border-b border-zinc-100 p-2 dark:border-white/10">
                <flux:input
                    x-model="search"
                    placeholder="Search..."
                    icon="magnifying-glass"
                    autocomplete="off"
                />
            </div>

            {{-- Options --}}
            <div class="max-h-72 overflow-y-auto p-1">

                {{-- No parent --}}
                <button
                    type="button"
                    @click="
                        $wire.set('{{ $model }}', null);
                        open = false;
                    "
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-white/10"
                >
                    <flux:icon.folder class="size-4 text-amber-500" />

                    <span>
                        {{ __('dashboard::strings.Category Without Parent') }}
                    </span>
                </button>

                <template
                    x-for="category in filteredCategories()"
                    :key="category.id"
                >
                    <button
                        type="button"
                        @click="
                            $wire.set('{{ $model }}', category.slug);
                            open = false;
                        "
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-white/10"
                        :style="`padding-inline-start: ${12 + category.depth * 20}px`"
                    >
                        <flux:icon.folder class="size-4 shrink-0 text-zinc-400" />

                        <span
                            class="truncate"
                            x-text="category.name"
                        ></span>
                    </button>
                </template>

                <div
                    x-show="filteredCategories().length === 0"
                    class="px-3 py-8 text-center text-sm text-zinc-400"
                >
                    No categories found.
                </div>

            </div>
        </div>

        <flux:error :name="$model" />

    </flux:field>
</div>
