<?php

namespace Modules\User\Livewire\Forms;

use App\Rules\JalaliDate;
use Livewire\Form;
use Modules\Car\Models\UserCar;
use Modules\User\Services\UserCarService;
use Morilog\Jalali\Jalalian;

class UserCarForm extends Form
{
    public ?int $id;

    public ?string $car;

    public string $car_company;

    public string $manufactured_at;

    public int $color_id;

    public ?string $description = null;

    public string $manufactured_at_gregorian = '';

    /**
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        return [
            'car_company' => ['required', 'exists:Modules\Car\Models\CarCompany,slug'],
            'car' => ['required', 'exists:Modules\Car\Models\Car,slug'],
            'manufactured_at' => ['required', new JalaliDate],
        ];
    }

    public function setUserCar(UserCar $userCar): void
    {
        $this->id = $userCar->id;
        $this->car_company = $userCar->car->company->slug;
        $this->car = $userCar->car->slug;
        $manufacturedAt = $userCar->manufactured_at;
        $this->manufactured_at = $manufacturedAt ? Jalalian::fromDateTime($manufacturedAt)->format('Y/m/d') : '';
        $this->manufactured_at_gregorian = $manufacturedAt?->toISOString() ?? '';
        $this->description = $userCar->description;
    }

    /** @return UserCar|null */
    public function save()
    {
        $userCarService = resolve(UserCarService::class);
        $data = $this->validate();

        if ($this->id) {
            // Update
            $userCarService->update($this->id, $data);

            return null;
        }

        // Create
        return $userCarService->create($data);
    }
}
