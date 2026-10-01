<?php

namespace Modules\Car\Livewire\Forms;

use Modules\Car\Models\Car;
use Modules\Car\Services\CarService;
use Modules\Dashboard\Livewire\Forms\ImageForm;

class CarForm extends ImageForm
{
    public ?int $id = null;

    public ?Car $car = null;

    public string $name;

    public string $car_company;

    public array $new_images = [];

    /** @return array<mixed, string> */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'car_company' => 'required|string|exists:Modules\Car\Models\CarCompany,slug',
            ...$this->getImageRules($this->car?->images),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->validate();
        } catch (\Exception $exception) {
            throw $exception;
        }

        $carService = resolve(CarService::class);

        if ($this->id) {
            $carService->update($this->id, $data);
        } else {
            $carService->create($data);
        }
    }

    public function setCar(Car $car): void
    {
        $this->car = $car;
        $this->id = $car->id;
        $this->name = $car->name;
        $this->car_company = $car->company->slug;
        $this->setPreviousImages($car->images);
    }
}
