<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Modules\Inventory\Database\Factories\ItemBrandFactory;

// use Modules\Inventory\Database\Factories\ItemBrandFactory;

/**
 * @property-read Brand $brand
 * @property-read Item $item
 *
 * @extends BaseModel<ItemBrand>
 */
#[Fillable('slug', 'item_id', 'brand_id', 'sku', 'barcode', 'part_number', 'purchase_price', 'sale_price', 'is_active')]
class ItemBrand extends BaseModel
{
    use AsPivot;

    /**
     * @use HasFactory<ItemBrandFactory>
     */
    use HasFactory;

    protected $table = 'item_brands';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): ItemBrandFactory
    {
        return ItemBrandFactory::new();
    }

    /** @return BelongsTo<Item, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /** @return BelongsTo<Brand, $this> */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
