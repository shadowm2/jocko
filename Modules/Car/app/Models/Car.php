<?php

namespace Modules\Car\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Car\Database\Factories\CarFactory;
use Modules\Dashboard\Models\Media;

/**
 * @property int $id
 * @property int $car_company_id
 * @property string $name
 * @property string $slug
 *
 * @extends BaseModel<Car>
 */
#[Fillable(['car_company_id', 'slug', 'name'])]

class Car extends BaseModel
{
    /**
     * @use HasFactory<CarFactory>
     */
    use HasFactory;

    protected static function newFactory(): CarFactory
    {
        return CarFactory::new();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CarCompany::class, 'car_company_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }
}
