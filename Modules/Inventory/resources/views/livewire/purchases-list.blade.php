<div>
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Purchases List')"
        row-component="inventory::components.purchase-row"
    >
        <x-slot name="toolbar">
            <x-table.filter-drawer :values="$filters" :filters="$filterDefinitions">
                <x-table.filters
                :filters="$filterDefinitions"
                :sort-options="$sortOptions"
                :sort-by="$sortBy"
                :sort-direction="$sortDirection"
                clear-action="clearFilters"
                panel
            />
            </x-table.filter-drawer>
        </x-slot>

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

                <x-inventory::purchase-items-editor
                    :form="$this->form"
                    :$itemCatalog
                    picker-key="purchase-item-editor-{{ $this->form->purchase->id }}"
                />

                <div class="flex flex-col-reverse gap-2 pt-3 sm:flex-row sm:justify-end">
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
