<?php

namespace Modules\User\Livewire\Forms;

use App\Rules\JalaliDate;
use Carbon\Carbon;
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
        $this->manufactured_at = Jalalian::fromCarbon(Carbon::createFromImmutable($userCar->manufactured_at))->format('Y/m/d');
        $this->manufactured_at_gregorian = $userCar->manufactured_at->toISOString();
        $this->description = $userCar->description;
    }

    public function save()
    {
        $userCarService = resolve(UserCarService::class);
        $data = $this->validate();

        if ($this->id) {
            // Update
            $userCarService->update($this->id, $data);
        } else {
            // Create

            return $userCarService->create($data);
        }
    }
}
