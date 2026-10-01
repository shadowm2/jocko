<flux:field>
    <flux:label>
        {{ __('inventory::strings.Select Warehouse') }}
    </flux:label>
    <flux:select
        wire:model.live="form.warehouse"
        :placeholder="__('inventory::strings.Select Warehouse')"
    >
        @foreach ($warehouses as $warehouse)
            <flux:select.option value="{{ $warehouse->slug }}">
                {{ $warehouse->name }}
            </flux:select.option>
        @endforeach
    </flux:select>
    <flux:error name="form.warehouse" />
</flux:field>

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
    <flux:input
        wire:model="form.order_number"
        :placeholder="__('inventory::strings.Enter Purchase Order Number')"
    >

    </flux:input>
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

<livewire:inventory-warehouse-item-picker
    :warehouse="$this->form->warehouse"
    wire:model="form.items"
    :label="__('inventory::attributes.Warehouse Item')"
    :placeholder="__('inventory::strings.Select Warehouse Item')"
    wire:key="purchase-order-item-picker"
/>
