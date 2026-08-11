<?php

namespace Modules\Car\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Car\Database\Factories\CarCompanyFactory;

// use Modules\Car\Database\Factories\CarCompanyFactory;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 */
class CarCompany extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): CarCompanyFactory
    {
        return CarCompanyFactory::new();
    }
}
