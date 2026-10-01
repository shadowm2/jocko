<?php

namespace Modules\Car\Livewire\Forms;

use Modules\Car\Models\CarCompany;
use Modules\Car\Services\CarCompanyService;
use Modules\Dashboard\Livewire\Forms\ImageForm;

class CompanyForm extends ImageForm
{
    public ?int $id = null;

    public string $name;

    public ?CarCompany $company;

    public function rules()
    {
        return [
            'name' => 'required|unique:Modules\Car\Models\CarCompany,name'.
                ($this->id ? ",{$this->id}" : ''),
            ...$this->getImageRules($this->company->logo),
        ];
    }

    public function save(): void
    {
        try {

            $data = $this->validate();
        } catch (\Exception $exception) {
            dd($exception);
        }

        $companyService = resolve(CarCompanyService::class);

        if ($this->id) {
            $companyService->update($this->id, $data);
        } else {
            $companyService->create($data);
        }
    }

    public function setCompany(CarCompany $company): void
    {
        $this->company = $company;
        $this->id = $company->id;
        $this->name = $company->name;
        $this->setPreviousImages($company->logo);
    }
}
