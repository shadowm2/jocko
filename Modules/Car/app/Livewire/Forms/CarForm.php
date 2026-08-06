<?php

namespace Modules\Car\Livewire\Forms;

use Livewire\Form;
use Modules\Car\Models\Car;
use Modules\Car\Services\CarService;

class CarForm extends Form
{
    public ?int $id = null;

    public string $name;

    public string $car_company;

    /** @var array<mixed, string> */
    protected array $rules = [
        'name' => 'required',
        'car_company' => 'required|string|exists:Modules\Car\Models\CarCompany,slug',
    ];

    public function save(): void
    {
        $data = $this->validate();

        $carService = resolve(CarService::class);

        if ($this->id) {
            $carService->update($this->id, $data);
        } else {
            $carService->create($data);
        }
    }

    public function setCar(Car $car): void
    {
        $this->id = $car->id;
        $this->name = $car->name;
        $this->car_company = $car->company->slug;
    }
}
