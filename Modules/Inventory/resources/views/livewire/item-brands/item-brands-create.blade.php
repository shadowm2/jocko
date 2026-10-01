<div class="flex flex-col gap-5">
    <flux:heading size="xl">
        {{ __('inventory::strings.Item :item Brand Create', ['item' => $item->name]) }}
    </flux:heading>
    <x-inventory::item-brand-form :$brands />
</div>
