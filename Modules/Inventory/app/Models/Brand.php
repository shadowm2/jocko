<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Dashboard\Database\Factories\BrandFactory;
use Modules\Dashboard\Models\Media;

// use Modules\Inventory\Database\Factories\BrandFactory;
/**
 * @property string $name
 */
#[Fillable(['name', 'slug', 'is_active', 'description'])]
class Brand extends BaseModel
{
    /**
     * @use HasFactory<BrandFactory>
     */
    use HasFactory;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }
}
