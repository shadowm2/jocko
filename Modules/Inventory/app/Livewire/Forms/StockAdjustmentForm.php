<?php

namespace Modules\Inventory\Livewire\Forms;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Form;
use Modules\Inventory\Enums\StockMovementType;
use Modules\Inventory\Services\StockMovementService;
use Modules\Inventory\Services\WarehouseItemService;

class StockAdjustmentForm extends Form
{
    /** Reasons an adjustment can be recorded under; stock-in/out comes from the reason. */
    public const TYPES = [
        StockMovementType::ADJUSTMENT,
        StockMovementType::RECEIPT,
        StockMovementType::RETURN,
        StockMovementType::DAMAGE,
        StockMovementType::CONSUMPTION,
    ];

    public string $warehouse_item = '';

    public string $type = 'adjustment';

    public string $quantity = '';

    public string $description = '';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'warehouse_item' => 'required|exists:Modules\Inventory\Models\WarehouseItem,slug',
            'type' => ['required', Rule::in(array_column(self::TYPES, 'value'))],
            // A recount may end at zero; every other reason moves something.
            'quantity' => ['required', 'numeric', $this->type === StockMovementType::ADJUSTMENT->value ? 'min:0' : 'gt:0'],
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $type = StockMovementType::from($data['type']);
        $warehouseItem = resolve(WarehouseItemService::class)->findByKeyOrFail($data['warehouse_item']);
        $quantity = (float) $data['quantity'];

        // ponytail: base unit only; add a unit picker when counting in cartons matters.
        $delta = match ($type) {
            StockMovementType::ADJUSTMENT => $quantity - (float) $warehouseItem->quantity,
            StockMovementType::DAMAGE, StockMovementType::CONSUMPTION => -$quantity,
            default => $quantity,
        };

        try {
            $movement = resolve(StockMovementService::class)
                ->adjust($warehouseItem, $delta, $type, $data['description'] ?: null);
        } catch (ValidationException $e) {
            // Service errors are keyed like the rules above; bind them to this form.
            throw ValidationException::withMessages(Arr::prependKeysWith($e->errors(), 'form.'));
        }

        if (! $movement) {
            throw ValidationException::withMessages(['form.quantity' => __('inventory::messages.No Stock Change')]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'warehouse_item' => __('inventory::attributes.Warehouse Item'),
            'type' => __('inventory::attributes.Movement Type'),
            'quantity' => __('inventory::attributes.Movement Quantity'),
            'description' => __('inventory::attributes.Movement Description'),
        ];
    }
}
