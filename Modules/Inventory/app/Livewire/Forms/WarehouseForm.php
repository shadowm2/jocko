<?php

namespace Modules\Inventory\Livewire\Forms;

use Exception;
use Livewire\Form;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Services\WarehouseService;

class WarehouseForm extends Form
{
    public ?int $id = null;

    public string $name;

    public ?Warehouse $warehouse = null;

    public ?string $description;

    public bool $is_active = true;

    public function getRules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $data = $this->validate();
        $warehouseService = resolve(WarehouseService::class);

        if ($this->id) {
            $warehouseService->update($this->id, $data);
        } else {
            $warehouseService->create($data);
        }
    }

    /**
     * @throws Exception
     */
    public function setWarehouse(Warehouse $warehouse): void
    {
        $this->warehouse = $warehouse;
        $this->id = $warehouse->id;
        $this->name = $warehouse->name;
        $this->description = $warehouse->description;
        $this->is_active = $warehouse->is_active;
    }

    public function validationAttributes(): array
    {
        return [
            'name' => __('inventory::attributes.Warehouse Name'),
            'description' => __('inventory::attributes.Warehouse Description'),
            'is_active' => __('inventory::attributes.Is Active'),
        ];
    }
}
