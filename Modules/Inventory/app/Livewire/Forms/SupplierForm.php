<?php

namespace Modules\Inventory\Livewire\Forms;

use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\SupplierService;
use Modules\User\Livewire\Forms\UserForm;

class SupplierForm extends UserForm
{
    public ?int $supplier_id = null;

    public bool $is_active = true;

    public ?string $phone;

    public ?string $address;

    public ?string $tax_number;

    public ?string $postal_code;

    public ?string $notes;

    public ?string $website;

    public ?string $country = '';

    public ?string $province = '';

    public ?string $city = '';

    public function getRules(): array
    {
        return [
            ...parent::getRules(),
            'is_active' => ['required', 'boolean'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'website' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'province' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $supplierService = resolve(SupplierService::class);

        if ($this->supplier_id) {
            $supplierService->update($this->supplier_id, $data);
        } else {
            $supplierService->create($data);
        }
    }

    public function setSupplier(Supplier $supplier): void
    {
        $this->supplier_id = $supplier->id;
        $this->setUser($supplier->user);
        $this->phone = $supplier->phone;
        $this->postal_code = $supplier->postal_code;
        $this->website = $supplier->website;
        $this->address = $supplier->address;
        $this->tax_number = $supplier->tax_number;
        $this->notes = $supplier->notes;

        if ($supplier->city) {
            $this->country = $supplier->city->province->country->code;
            $this->province = $supplier->city->province->slug;
            $this->city = $supplier->city->slug;
        }
    }
}
