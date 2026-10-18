@php
    use App\Helpers\Utils;
@endphp
<form
    class="space-y-8"
    wire:submit="save"
>
    <flux:card class="space-y-4">
        <flux:field>
            <flux:label>{{ __('inventory::attributes.Source Warehouse Item') }}</flux:label>
            <flux:select
                :placeholder="__('inventory::strings.Select Warehouse Item')"
                wire:model.live="form.warehouse_item"
            >
                @foreach ($warehouseItems as $warehouseItem)
                    <flux:select.option :value="$warehouseItem->slug">
                        {{ $warehouseItem->warehouse->name }} — {{ $warehouseItem->item->name }}
                        ({{ __('inventory::strings.In Stock') }}: {{ Utils::formatQuantity($warehouseItem->quantity, 3, ',') }})
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="form.warehouse_item" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('inventory::attributes.Destination Warehouse') }}</flux:label>
            <flux:select
                :placeholder="__('inventory::strings.Select Destination Warehouse')"
                wire:model="form.warehouse"
            >
                @foreach ($warehouses as $warehouse)
                    <flux:select.option :value="$warehouse->slug">{{ $warehouse->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="form.warehouse" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('inventory::attributes.Movement Quantity') }}</flux:label>
            <flux:input
                wire:model="form.quantity"
                type="number"
                step="0.001"
                min="0"
            />
            <flux:error name="form.quantity" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('inventory::attributes.Movement Description') }}</flux:label>
            <flux:textarea
                :placeholder="__('inventory::strings.Enter Transfer Note')"
                wire:model="form.description"
            />
            <flux:error name="form.description" />
        </flux:field>
    </flux:card>

    <div class="flex justify-start gap-3">
        <flux:button
            type="submit"
            variant="primary"
        >
            {{ __('inventory::strings.Save Stock Transfer') }}
        </flux:button>
        <flux:button
            variant="danger"
            href="{{ route('stock-movements.index') }}"
        >
            {{ __('strings.Cancel') }}
        </flux:button>
    </div>
</form>
