<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Purchases List')"
        row-component="inventory::components.purchase-row"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('purchases.create')"
                variant="primary"
            >
                {{ __('inventory::strings.Purchase Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>

    <flux:modal
        name="purchase-items-editor"
        class="w-[calc(100vw-2rem)] max-w-4xl"
    >
        @if ($this->form->purchase)
            <form
                wire:submit="savePurchaseItems"
                class="space-y-4"
            >
                <div>
                    <flux:heading size="lg">{{ __('inventory::strings.Purchase Items') }}</flux:heading>
                    <flux:subheading>{{ $this->form->order_number }}</flux:subheading>
                </div>

                <livewire:inventory-warehouse-item-picker
                    :warehouse="$this->form->warehouse"
                    wire:model="form.selected"
                    :label="__('inventory::attributes.Warehouse Item')"
                    :placeholder="__('inventory::strings.Select Warehouse Item')"
                    wire:key="purchase-item-editor-{{ $this->form->purchase->id }}"
                />
                <flux:error name="form.selected" />

                @if (count($itemCatalog))
                    <div class="max-h-[55vh] space-y-1.5 overflow-auto pe-1">
                        @foreach ($itemCatalog as $slug => $details)
                            @php
                                $quantityError = 'form.items.' . $slug . '.quantity';
                                $unitPriceError = 'form.items.' . $slug . '.unit_price';
                            @endphp
                            <div
                                wire:key="purchase-modal-line-{{ $slug }}"
                                x-data="numericLine"
                                @localized-digits-input="setValue($event)"
                                class="grid grid-cols-2 gap-x-2 gap-y-1.5 rounded-lg border border-zinc-200 p-2 dark:border-white/10 sm:grid-cols-[minmax(0,1fr)_7rem_9rem] sm:items-center"
                            >
                                <div class="col-span-2 min-w-0 sm:col-span-1 sm:grid sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:gap-x-2 sm:self-stretch">
                                    <div class="flex min-w-0 items-center justify-between gap-2 sm:contents">
                                        <div class="truncate text-sm font-medium">{{ $details['name'] }}</div>
                                        <div class="flex shrink-0 items-center gap-1 text-xs text-zinc-500 sm:row-span-2">
                                            <span>{{ __('inventory::attributes.Purchase Total') }}:</span>
                                            <span
                                                class="max-w-24 truncate tabular-nums"
                                                x-text="total"
                                            >{{ \App\Helpers\Utils::formatQuantity($this->form->lineTotal($slug), 2, thousandsSeparator: ',') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-zinc-500">
                                        {{ __('inventory::attributes.Warehouse Item Quantity') }}:
                                        {{ \App\Helpers\Utils::formatQuantity($details['quantity'], thousandsSeparator: ',') }}
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <x-dashboard::numeric-input
                                        size="sm"
                                        data-numeric-field="quantity"
                                        wire:model.number="form.items.{{ $slug }}.quantity"
                                        :error="$quantityError"
                                        :placeholder="__('inventory::attributes.Purchase Quantity')"
                                        :aria-label="__('inventory::attributes.Purchase Quantity') .
                                            ' — ' .
                                            $details['name']"
                                    />
                                </div>
                                <div class="min-w-0">
                                    <x-dashboard::numeric-input
                                        size="sm"
                                        data-numeric-field="unit_price"
                                        wire:model.number="form.items.{{ $slug }}.unit_price"
                                        :error="$unitPriceError"
                                        :placeholder="__('inventory::attributes.Purchase Unit Price')"
                                        :aria-label="__('inventory::attributes.Purchase Unit Price') .
                                            ' — ' .
                                            $details['name']"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500">{{ __('inventory::strings.No items found.') }}</p>
                @endif

                <div class="flex flex-col-reverse gap-2 border-t pt-3 sm:flex-row sm:justify-end">
                    <flux:modal.close>
                        <flux:button
                            type="button"
                            variant="filled"
                        >{{ __('dashboard::strings.Close') }}</flux:button>
                    </flux:modal.close>
                    <flux:button
                        type="submit"
                        variant="primary"
                    >{{ __('inventory::strings.Save Purchase') }}</flux:button>
                </div>
            </form>
        @endif
    </flux:modal>
</div>
