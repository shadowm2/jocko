<flux:field class="space-x-2">
    <flux:label class="text-xs">
        {{ __('inventory::attributes.Purchase Status') }}
    </flux:label>

    {{-- Read-only summary: deliberately not an input, since there is no input to edit. --}}
    <flux:badge
        size="lg"
        inset
        :color="$this->form->statusColor()"
        :label="$this->form->statusLabel()"
    />
</flux:field>

<livewire:inventory-warehouse-item-picker
    :show-warehouse-picker="true"
    wire:model="form.selected"
    :label="__('inventory::attributes.Warehouse Item')"
    :placeholder="__('inventory::strings.Select Warehouse Item')"
    wire:key="purchase-order-item-picker"
>
    <x-slot:warehouse-error>
        <flux:error name="form.warehouse" />
    </x-slot:warehouse-error>
</livewire:inventory-warehouse-item-picker>

@if (count($itemCatalog))
    <flux:field>
        <flux:label>
            {{ __('inventory::strings.Purchase Items') }}
        </flux:label>

        <div class="space-y-0 overflow-hidden rounded-b-lg">
            {{-- Column headings sit once above the rows instead of on every row. --}}
            <div
                class="grid grid-cols-[minmax(0,1fr)_12rem_12rem_14rem] items-center gap-2 rounded-t-lg border border-zinc-200 bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-500 dark:border-white/10 dark:bg-white/5 dark:text-zinc-400">
                <span>{{ __('inventory::attributes.Warehouse Item') }}</span>
                <span>{{ __('inventory::attributes.Purchase Quantity') }}</span>
                <span>{{ __('inventory::attributes.Purchase Unit Price') }}</span>
                <span>{{ __('inventory::attributes.Purchase Total') }}</span>
            </div>

            @foreach ($itemCatalog as $slug => $details)
                {{-- Error keys are per line, so they need the concrete slug
                     rather than the wildcard form used in the rules. --}}
                @php
                    $quantityError = 'form.items.' . $slug . '.quantity';
                    $unitPriceError = 'form.items.' . $slug . '.unit_price';
                    $hasError = $errors->has($quantityError) || $errors->has($unitPriceError);
                @endphp

                <div
                    wire:key="purchase-line-{{ $slug }}"
                    x-data="numericLine"
                    @localized-digits-input="setValue($event)"
                    @class([
                        'grid grid-cols-[minmax(0,1fr)_12rem_12rem_14rem] items-start gap-x-2 gap-y-0.5 border-x border-b border-zinc-200 px-2 py-1.5 dark:border-white/10',
                        'bg-red-50/70 dark:bg-red-950/20' => $hasError,
                    ])
                >
                    <div class="flex min-w-0 items-baseline gap-1.5 pt-1.5">
                        <span class="truncate text-sm text-zinc-900 dark:text-white">
                            {{ $details['name'] }}
                        </span>

                        <span class="shrink-0 text-xs text-zinc-400">
                            ({{ \App\Helpers\Utils::formatQuantity($details['quantity'], thousandsSeparator: ',') }})
                        </span>
                    </div>

                    <div>
                        <x-dashboard::numeric-input
                            size="sm"
                            data-numeric-field="quantity"
                            wire:model.number="form.items.{{ $slug }}.quantity"
                            :error="$quantityError"
                            :placeholder="__('inventory::attributes.Purchase Quantity')"
                            :aria-label="__('inventory::attributes.Purchase Quantity') . ' — ' . $details['name']"
                            @class([
                                '[&_input]:px-2 [&_input]:text-center [&_input]:rounded-sm [&_input]:h-8',
                                '[&_input]:border-red-400 dark:[&_input]:border-red-500' => $errors->has(
                                    $quantityError),
                            ])
                        />
                    </div>

                    <div>
                        <x-dashboard::numeric-input
                            size="sm"
                            data-numeric-field="unit_price"
                            wire:model.number="form.items.{{ $slug }}.unit_price"
                            :error="$unitPriceError"
                            :placeholder="__('inventory::attributes.Purchase Unit Price')"
                            :aria-label="__('inventory::attributes.Purchase Unit Price') . ' — ' . $details['name']"
                            @class([
                                '[&_input]:px-2 [&_input]:text-center [&_input]:rounded-sm [&_input]:h-8',
                                '[&_input]:border-red-400 dark:[&_input]:border-red-500' => $errors->has(
                                    $unitPriceError),
                            ])
                        />
                    </div>

                    {{-- Total is derived from quantity x unit price, so it is shown, not edited. --}}
                    <div
                        class="flex h-8 items-center justify-start rounded-sm border border-dashed border-zinc-300 bg-zinc-50 px-2 text-sm tabular-nums text-zinc-700 dark:border-white/15 dark:bg-white/5 dark:text-zinc-200"
                        aria-live="polite"
                        x-text="total"
                    >
                        {{ \App\Helpers\Utils::formatQuantity((float) ($this->form->items[$slug]['total'] ?? 0), 2) }}
                    </div>
                </div>
            @endforeach
        </div>
    </flux:field>
@endif

<flux:field>
    <flux:label>
        {{ __('inventory::strings.Select Supplier') }}
    </flux:label>
    <flux:select
        wire:model="form.supplier"
        :placeholder="__('inventory::strings.Select Supplier')"
    >
        @foreach ($suppliers as $supplier)
            <flux:select.option value="{{ $supplier->slug }}">
                {{ $supplier->user->fullName() }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.supplier" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Purchase Order Number') }}
    </flux:label>
    <x-dashboard::numeric-input
        wire:model="form.order_number"
        :placeholder="__('inventory::strings.Enter Purchase Order Number')"
    >

    </x-dashboard::numeric-input>
    <flux:error name="form.order_number" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Purchase Ordered At') }}
    </flux:label>
    <input
        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
        wire:model="form.ordered_at"
        value="{{ $this->form->ordered_at_gregorian }}"
        placeholder="{{ __('inventory::strings.Select Purchase Ordered At') }}"
    />
    <flux:error name="form.ordered_at" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Purchase Expected At') }}
    </flux:label>
    <input
        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
        wire:model="form.expected_at"
        value="{{ $this->form->expected_at_gregorian }}"
        placeholder="{{ __('inventory::strings.Select Purchase Expected At') }}"
    />
    <flux:error name="form.expected_at" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Purchase Received At') }}
    </flux:label>
    <input
        class="persian-datepicker
                    appearance-none [:where(&)]:w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-[1.375rem]
                    rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[7%]
                    text-zinc-700 dark:text-zinc-300 disabled:text-zinc-500 dark:disabled:text-zinc-400
                    has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400
                    dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border
                    border-zinc-200 border-b-zinc-300/80 dark:border-white/10"
        wire:model="form.received_at"
        value="{{ $this->form->received_at_gregorian }}"
        placeholder="{{ __('inventory::strings.Select Purchase Received At') }}"
    />
    <flux:error name="form.received_at" />
</flux:field>

<flux:field>
    <flux:label>
        {{ __('inventory::attributes.Purchase Notes') }}
    </flux:label>
    <flux:textarea
        wire:model="form.notes"
        :placeholder="__('inventory::strings.Enter Purchase Notes')"
    />
    <flux:error name="form.notes" />
</flux:field>
