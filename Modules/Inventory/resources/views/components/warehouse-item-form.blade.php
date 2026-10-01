<form
    class="space-y-8"
    wire:submit="save"
    xmlns:flux="http://www.w3.org/1999/html"
>
    <flux:card class="space-y-4">
        <x-inventory::warehouse-item-fields
            :$items
            :$warehouses
            :$currentWarehouse
        />
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('inventory::strings.Save Warehouse Item') }}
        </flux:button>
        <flux:button
            variant="danger"
            href="{{ url()->previous() }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
