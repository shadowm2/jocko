<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Database\Factories\UnitFactory;

/**
 * @extends BaseModel<Unit>
 *
 * @property $name
 * @property $symbol
 * @property $unit_group_id
 * @property $conversion_factor
 * @property $is_base
 * @property $is_active
 */
#[Fillable(['name', 'slug', 'symbol', 'unit_group_id', 'conversion_factor', 'is_base', 'is_active'])]
class Unit extends BaseModel
{
    /**
     * @use HasFactory<UnitFactory>
     */
    use HasFactory;

    protected static function newFactory(): UnitFactory
    {
        return UnitFactory::new();
    }

    public function unitGroup(): BelongsTo
    {
        return $this->belongsTo(UnitGroup::class);
    }
}
