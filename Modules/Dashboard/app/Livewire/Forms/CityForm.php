<?php

namespace Modules\Dashboard\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Services\CityService;
use Modules\Dashboard\Services\CountryService;

class CityForm extends Form
{
    public ?int $id = null;

    public ?string $name;

    public ?string $country;

    public ?string $province;

    public ?bool $is_active = true;

    /**
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        return [
            'name' => 'required|string',
            'province' => [
                'required',
                Rule::exists(Province::class, 'slug')
                    ->where(
                        'country_id',
                        resolve(CountryService::class)->findByKey($this->country)?->id
                    ),
            ],
            'country' => 'required',
            'is_active' => 'required|boolean',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $cityService = resolve(CityService::class);

        if ($this->id) {
            $cityService->update($this->id, $data);
        } else {
            $cityService->create($data);
        }
    }

    public function setCity(City $city): void
    {
        $this->id = $city->id;
        $this->name = $city->name;
        $this->country = $city->province?->country?->code;
        $this->province = $city->province?->slug;
        $this->is_active = $city->is_active;
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'name' => __('dashboard::attributes.City Name'),
            'province' => __('dashboard::attributes.City Province'),
            'country' => __('dashboard::attributes.Province Country'),
        ];
    }
}
