<?php

namespace Modules\Inventory\Livewire\Forms;

use Livewire\Form;
use Modules\Inventory\Models\UnitGroup;
use Modules\Inventory\Services\UnitGroupService;

class UnitGroupForm extends Form
{
    public ?int $id = null;

    public string $name;

    /** @var array<mixed, string> */
    protected array $rules = [
        'name' => 'required',
    ];

    public function save(): void
    {
        $data = $this->validate();

        $unitGroupService = resolve(UnitGroupService::class);

        if ($this->id) {
            $unitGroupService->update($this->id, $data);
        } else {
            $unitGroupService->create($data);
        }
    }

    public function setUnitGroup(UnitGroup $unitGroup): void
    {
        $this->id = $unitGroup->id;
        $this->name = $unitGroup->name;
    }
}
