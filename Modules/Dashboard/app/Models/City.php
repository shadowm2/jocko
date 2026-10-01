<?php

namespace Modules\Dashboard\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Dashboard\Database\Factories\CityFactory;

/**
 * @extends BaseModel<City>
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $province_id
 * @property bool $is_active
 */
#[Fillable(['name', 'slug', 'province_id', 'is_active'])]
class City extends BaseModel
{
    /** @extends HasFactory<CityFactory> */
    use HasFactory;

    protected static function newFactory(): CityFactory
    {
        return CityFactory::new();
    }

    /**
     * @return BelongsTo<Province, $this>
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
