<?php

namespace Modules\Inventory\Livewire\Forms;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Livewire\Form;
use Modules\Inventory\Services\StockMovementService;
use Modules\Inventory\Services\WarehouseItemService;
use Modules\Inventory\Services\WarehouseService;

class StockTransferForm extends Form
{
    public string $warehouse_item = '';

    public string $warehouse = '';

    public string $quantity = '';

    public string $description = '';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'warehouse_item' => 'required|exists:Modules\Inventory\Models\WarehouseItem,slug',
            'warehouse' => 'required|exists:Modules\Inventory\Models\Warehouse,slug',
            'quantity' => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        try {
            // ponytail: base unit only; add a unit picker when moving cartons matters.
            resolve(StockMovementService::class)->transfer(
                resolve(WarehouseItemService::class)->findByKey($data['warehouse_item']),
                resolve(WarehouseService::class)->findByKey($data['warehouse']),
                (float) $data['quantity'],
                $data['description'] ?: null,
            );
        } catch (ValidationException $e) {
            // Service errors are keyed like the rules above; bind them to this form.
            throw ValidationException::withMessages(Arr::prependKeysWith($e->errors(), 'form.'));
        }
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'warehouse_item' => __('inventory::attributes.Source Warehouse Item'),
            'warehouse' => __('inventory::attributes.Destination Warehouse'),
            'quantity' => __('inventory::attributes.Movement Quantity'),
            'description' => __('inventory::attributes.Movement Description'),
        ];
    }
}
