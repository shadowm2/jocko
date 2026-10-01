<?php

namespace Modules\Dashboard\Livewire\Forms;

use Livewire\Form;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Services\ProvinceService;

class ProvinceForm extends Form
{
    public ?int $id = null;

    public string $name;

    public string $country;

    public ?bool $is_active = true;

    /** @var array<mixed, string> */
    protected array $rules = [
        'name' => 'required|string',
        'country' => 'required|string|exists:Modules\Dashboard\Models\Country,code',
        'is_active' => 'required|boolean',
    ];

    public function save(): void
    {
        $data = $this->validate();

        $provinceService = resolve(ProvinceService::class);

        if ($this->id) {
            $provinceService->update($this->id, $data);
        } else {
            $provinceService->create($data);
        }
    }

    public function setProvince(Province $province): void
    {
        $this->id = $province->id;
        $this->name = $province->name;
        $this->is_active = $province->is_active;
        $this->country = $province->country->code;
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => __('dashboard::attributes.Province Name'),
            'country' => __('dashboard::attributes.Province Country'),
        ];
    }
}
