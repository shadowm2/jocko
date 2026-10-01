<?php

namespace Modules\Inventory\Livewire\Forms;

use Exception;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Dashboard\Livewire\Forms\ImageForm;
use Modules\Dashboard\Models\Media;
use Modules\Dashboard\Services\BrandService;
use Modules\Inventory\Models\Brand;

class BrandForm extends ImageForm
{
    public ?int $id = null;

    public string $name;

    public ?Brand $brand = null;

    public ?string $description;

    public Media|TemporaryUploadedFile|null $logo;

    public bool $is_active = true;

    public function getRules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            ...$this->getImageRules($this->brand?->logo),
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $data = $this->validate();
        $brandService = resolve(BrandService::class);

        if ($this->id) {
            $brandService->update($this->id, $data);
        } else {
            $brandService->create($data);
        }
    }

    /**
     * @throws Exception
     */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
        $this->id = $brand->id;
        $this->name = $brand->name;
        $this->description = $brand->description;
        $this->is_active = $brand->is_active;
        $this->setPreviousImages($brand->logo);
    }

    public function validationAttributes(): array
    {
        return [
            'name' => __('inventory::attributes.Brand Name'),
            'description' => __('inventory::attributes.Brand Description'),
            'is_active' => __('inventory::attributes.Is Active'),
            'logo' => __('inventory::attributes.Brand Logo'),
        ];
    }
}
