<?php

namespace Modules\Car\Livewire\Forms;

use Livewire\Form;
use Modules\Car\Models\CarCompany;
use Modules\Car\Services\CarCompanyService;

class CompanyForm extends Form
{
    public ?int $id = null;

    public string $name;

    public function getRules()
    {
        return [
            'name' => 'required|unique:Modules\Car\Models\CarCompany,name'.
                ($this->id ? ",{$this->id}" : ''),
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $companyService = resolve(CarCompanyService::class);

        if ($this->id) {
            $companyService->update($this->id, $data);
        } else {
            $companyService->create($data);
        }
    }

    public function setCompany(CarCompany $company): void
    {
        $this->id = $company->id;
        $this->name = $company->name;
    }
}
