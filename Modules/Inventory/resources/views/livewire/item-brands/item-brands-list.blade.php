<div>
    <div class="flex flex-row gap-4">
        <flux:heading>
            <flux:text>
                {{ __('inventory::attributes.Item Name') }}
            </flux:text>
            <flux:link :href="route('items.update', ['item' => $item])">
                <span class="text-bold text-lg">
                    {{ $item->name }}
                </span>
            </flux:link>
        </flux:heading>
        <img
            src="{{ $item->images->first()->publicPath() }}"
            class="h-16 rounded-sm"
            alt="{{ $item->name }}"
        />
    </div>
    <flux:separator class="my-2" />
    <x-table.data-table
        :$columns
        :$rows
        :title="__('inventory::strings.Item Brands')"
        row-component="inventory::components.item-brand-row"
        :row-props="compact('item')"
    >
        <x-slot name="action">
            <flux:button
                icon:trailing="plus"
                :href="route('items.brands.create', ['item' => $item])"
                variant="primary"
            >
                {{ __('inventory::strings.Item Brand Add') }}
            </flux:button>
        </x-slot>
    </x-table.data-table>
</div>
