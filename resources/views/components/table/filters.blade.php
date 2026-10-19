@props([
    'filters' => [],
    'model' => 'filters',
    'sortOptions' => [],
    'sortBy' => '',
    'sortDirection' => 'asc',
    'clearAction' => null,
    'panel' => false,
])

<fieldset
    wire:loading.class="opacity-60"
    class="w-full space-y-3 rounded-lg border border-zinc-200 p-4 dark:border-zinc-700"
>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 {{ $panel ? '' : 'lg:grid-cols-4' }}">
        @foreach ($filters as $filter)
            @php
                $name = $filter['name'];
                $label = $filter['label'] ?? $name;
                $type = $filter['type'] ?? 'text';
                $wireModel = $model . '.' . $name;
            @endphp

            <div class="{{ $type === 'checkboxes' ? 'sm:col-span-2' : '' }}">
            @if ($type === 'checkboxes')
                <fieldset class="space-y-2">
                    <legend class="text-sm font-medium">{{ $label }}</legend>
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        @foreach ($filter['options'] ?? [] as $value => $optionLabel)
                            <label class="inline-flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    value="{{ $value }}"
                                    wire:model.live="{{ $wireModel }}"
                                    class="rounded border-zinc-300 text-accent focus:ring-accent"
                                >
                                <span>{{ $optionLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @elseif ($type === 'select')
                <flux:field>
                    <flux:label>{{ $label }}</flux:label>
                    <flux:select wire:model.live="{{ $wireModel }}">
                        <flux:select.option value="">{{ $filter['placeholder'] ?? __('strings.All') }}
                        </flux:select.option>
                        @foreach ($filter['options'] ?? [] as $value => $optionLabel)
                            <flux:select.option value="{{ $value }}">{{ $optionLabel }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @elseif ($type === 'date')
                <flux:field>
                    <flux:label>{{ $label }}</flux:label>
                    <input
                        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
                        wire:model.live="{{ $wireModel }}"
                        value="{{ data_get($this, $wireModel) }}"
                        placeholder="{{ $filter['placeholder'] ?? __('inventory::strings.Select Purchase Ordered At') }}"
                    />
                </flux:field>
            @else
                <flux:field>
                    <flux:label>{{ $label }}</flux:label>
                    <flux:input
                        type="{{ $type === 'search' ? 'search' : 'text' }}"
                        wire:model.live.debounce.400ms="{{ $wireModel }}"
                        placeholder="{{ $filter['placeholder'] ?? '' }}"
                    />
                </flux:field>
            @endif
            </div>
        @endforeach

        @if ($sortOptions)
            <flux:field>
                <flux:label>{{ __('strings.Sort By') }}</flux:label>
                <flux:select wire:model.live="sortBy">
                    @foreach ($sortOptions as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            <div class="flex items-end">
                <flux:button
                    type="button"
                    variant="filled"
                    wire:click="toggleSortDirection"
                >
                    {{ $sortDirection === 'asc' ? __('strings.Ascending') : __('strings.Descending') }}
                </flux:button>
            </div>
        @endif
    </div>

    @if ($clearAction)
        <div class="flex justify-end">
            <flux:button
                type="button"
                variant="ghost"
                wire:click="{{ $clearAction }}"
            >
                {{ __('strings.Clear Filters') }}
            </flux:button>
        </div>
    @endif
</fieldset>
