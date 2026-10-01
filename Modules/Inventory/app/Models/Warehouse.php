<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Database\Factories\WarehouseFactory;

// use Modules\Inventory\Database\Factories\WarehouseFactory;

/**
 * @extends BaseModel<Warehouse>
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property bool $is_active
 */
#[Fillable(['name', 'description', 'is_active', 'slug'])]
class Warehouse extends BaseModel
{
    /** @use HasFactory<WarehouseFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): WarehouseFactory
    {
        return WarehouseFactory::new();
    }

    public function warehouseItems(): HasMany
    {
        return $this->hasMany(WarehouseItem::class);
    }
}
