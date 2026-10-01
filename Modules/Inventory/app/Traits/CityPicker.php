<?php

namespace Modules\Inventory\Traits;

use Illuminate\Support\Collection;
use Modules\Dashboard\Services\CityService;
use Modules\Dashboard\Services\ProvinceService;

trait CityPicker
{
    public Collection|array $countries = [];

    public Collection|array $provinces = [];

    public Collection|array $cities = [];

    public function updatedFormCountry($value): void
    {
        $this->setCountry($value);
    }

    public function updatedFormProvince($value): void
    {
        $this->setProvince($value);
    }

    public function setCountry($value): void
    {
        if (empty($value)) {
            return;
        }
        $provinceService = resolve(ProvinceService::class);
        $this->provinces = $provinceService->getProvinces([
            'is_active' => true,
            'country' => $value,
        ],
            paginate: false
        );
        $this->setProvince($this->provinces->first()?->slug);
    }

    public function setProvince($value): void
    {
        if (empty($value)) {
            return;
        }
        $cityService = resolve(CityService::class);
        $this->cities = $cityService->getCities([
            'is_active' => true,
            'province' => $value,
        ],
            paginate: false
        );
    }

    public function setCity($value): void
    {
        if (empty($value)) {
            return;
        } elseif (! isset($this->form->city) || $value !== $this->form->city) {
            $this->form->city = $value;
        }
    }
}
