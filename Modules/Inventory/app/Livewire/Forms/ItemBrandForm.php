<?php

namespace Modules\Inventory\Livewire\Forms;

use Livewire\Form;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\ItemBrand;
use Modules\Inventory\Services\ItemBrandService;

class ItemBrandForm extends Form
{
    public ?int $id = null;

    public Item $item;

    public string $brand = '';

    public string $sku = '';

    public string $barcode = '';

    public string $part_number = '';

    public int $purchase_price;

    public int $sale_price;

    public bool $is_active = true;

    public function rules(): array
    {
        $rules = [
            'brand' => 'required|exists:Modules\Inventory\Models\Brand,slug',
            'sku' => 'required|unique:Modules\Inventory\Models\ItemBrand,sku',
            'barcode' => 'required|unique:Modules\Inventory\Models\ItemBrand,barcode',
            'part_number' => 'required|unique:Modules\Inventory\Models\ItemBrand,part_number',
            'is_active' => 'required|boolean',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ];
        if (! empty($this->id)) {
            $rules['sku'] .= ','.$this->id;
            $rules['barcode'] .= ','.$this->id;
            $rules['part_number'] .= ','.$this->id;
        }

        return $rules;
    }

    public function save(): void
    {
        $data = $this->validate();

        $itemBrandService = resolve(ItemBrandService::class);

        if ($this->id) {
            $itemBrandService->update($this->id, $data);
        } else {
            $itemBrandService->createForItem($data, $this->item);
        }
    }

    public function setItem(Item $item): void
    {
        $this->item = $item;
    }

    public function setItemBrand(ItemBrand $itemBrand): void
    {
        $this->id = $itemBrand->id;
        $this->brand = $itemBrand->brand->slug;
        $this->sku = $itemBrand->sku;
        $this->barcode = $itemBrand->barcode;
        $this->part_number = $itemBrand->part_number;
        $this->purchase_price = $itemBrand->purchase_price;
        $this->sale_price = $itemBrand->sale_price;
        $this->is_active = $itemBrand->is_active;
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
