<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Database\Factories\UnitGroupFactory;

/**
 * @extends  BaseModel<UnitGroup>
 *
 * @property int $id
 * @property string $name
 */
#[Fillable(['name', 'slug'])]
class UnitGroup extends BaseModel
{
    /**
     * @use HasFactory<UnitGroupFactory>
     * @use SoftDeletes
     */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): UnitGroupFactory
    {
        return UnitGroupFactory::new();
    }

    /**
     * @return HasMany<Unit, $this>
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
