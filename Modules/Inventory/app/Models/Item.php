<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Dashboard\Models\Category;
use Modules\Dashboard\Models\Media;
use Modules\User\Database\Factories\ItemFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @extends BaseModel<Item>
 */
#[Fillable(['name', 'slug', 'unit_group_id', 'category_id', 'description', 'is_active'])]
class Item extends BaseModel
{
    /**
     * @use HasFactory<ItemFactory>
     */
    use HasFactory;

    protected static function newFactory(): ItemFactory
    {
        return ItemFactory::new();
    }

    public function unitGroup(): BelongsTo
    {
        return $this->belongsTo(UnitGroup::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function brands(): HasManyThrough
    {
        return $this->hasManyThrough(Brand::class, ItemBrand::class, 'item_id', 'id', 'id', 'brand_id');
    }
}
