<?php

namespace Modules\Inventory\Livewire\Forms;

use Livewire\Form;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Services\UnitService;

class UnitForm extends Form
{
    public ?int $id = null;

    public string $name;

    public string $symbol;

    public string $unit_group;

    public float $conversion_factor;

    public bool $is_active = true;

    public bool $is_base = false;

    /** @var array<mixed, string> */
    protected array $rules = [
        'name' => 'required',
        'symbol' => 'nullable|string',
        'unit_group' => 'required|string|exists:Modules\Inventory\Models\UnitGroup,slug',
        'conversion_factor' => 'nullable|numeric',
        'is_active' => 'nullable|boolean',
        'is_base' => 'nullable|boolean',
    ];

    public function save(): void
    {
        $data = $this->validate();

        $unitService = resolve(UnitService::class);

        if ($this->id) {
            $unitService->update($this->id, $data);
        } else {
            $unitService->create($data);
        }
    }

    public function setUnit(Unit $unit): void
    {
        $this->id = $unit->id;
        $this->name = $unit->name;
        $this->symbol = $unit->symbol;
        $this->unit_group = $unit->unitGroup->slug;
        $this->conversion_factor = $unit->conversion_factor;
        $this->is_active = $unit->is_active;
        $this->is_base = $unit->is_base;
    }
}
