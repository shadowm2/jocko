<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Models\Brand;
use Modules\Inventory\Models\ItemBrand;

/** @extends BaseFilter<ItemBrand> */
class ItemBrandFilter extends BaseFilter
{
    protected function filter(): void
    {
        if (isset($this->filters['item_id'])) {
            $this->builder->where('item_id', $this->filters['item_id']);
        }

        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhereHas('brand', fn (Builder $brand) => $brand->where('name', 'like', "%{$search}%"));
            });
        }

        $this->whereCheckboxes('is_active');

        $this->sort([
            'brand' => Brand::query()->select('name')->whereColumn('brands.id', $this->builder->qualifyColumn('brand_id')),
            'sku' => 'sku',
            'purchase_price' => 'purchase_price',
            'sale_price' => 'sale_price',
        ]);
    }
}
