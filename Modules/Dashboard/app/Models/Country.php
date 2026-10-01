<?php

namespace Modules\Dashboard\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dashboard\Database\Factories\CountryFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $code3
 * @property string $phone_code
 * @property bool $is_active
 */
#[Fillable(['name', 'code', 'code3', 'phone_code', 'is_active'])]
class Country extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
