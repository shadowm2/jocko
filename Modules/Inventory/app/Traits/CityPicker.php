<?php

namespace Modules\Inventory\Traits;

use Illuminate\Support\Collection;
use Modules\Dashboard\Models\City;
use Modules\Dashboard\Models\Country;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Services\CityService;
use Modules\Dashboard\Services\ProvinceService;

trait CityPicker
{
    /** @var Collection<int, Country>|list<Country> */
    public Collection|array $countries = [];

    /** @var Collection<int, Province>|list<Province> */
    public Collection|array $provinces = [];

    /** @var Collection<int, City>|list<City> */
    public Collection|array $cities = [];

    /** @param string|null $value */
    public function updatedFormCountry($value): void
    {
        $this->setCountry($value);
    }

    /** @param string|null $value */
    public function updatedFormProvince($value): void
    {
        $this->setProvince($value);
    }

    /** @param string|null $value */
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

    /** @param string|null $value */
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

    /** @param string|null $value */
    public function setCity($value): void
    {
        if (empty($value)) {
            return;
        } elseif (! isset($this->form->city) || $value !== $this->form->city) {
            $this->form->city = $value;
        }
    }
}
