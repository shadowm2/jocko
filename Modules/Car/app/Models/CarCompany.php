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
 */
#[Fillable('name', 'slug')]
class CarCompany extends BaseModel
{
    use HasFactory;

    protected static function newFactory(): CarCompanyFactory
    {
        return CarCompanyFactory::new();
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
