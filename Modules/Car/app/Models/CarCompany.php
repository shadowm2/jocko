<?php

namespace Modules\Car\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Car\Database\Factories\CarCompanyFactory;
use Modules\Dashboard\Models\Media;

// use Modules\Car\Database\Factories\CarCompanyFactory;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 *
 * @extends BaseModel<CarCompany>
 */
#[Fillable('name', 'slug')]
class CarCompany extends BaseModel
{
    /** @use HasFactory<CarCompanyFactory> */
    use HasFactory;

    protected static function newFactory(): CarCompanyFactory
    {
        return CarCompanyFactory::new();
    }

    /** @return MorphOne<Media, $this> */
    public function logo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    /** @return HasMany<Car, $this> */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
