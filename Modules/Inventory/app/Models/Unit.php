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
 * @property string $name
 * @property string $symbol
 * @property int $unit_group_id
 * @property float $conversion_factor
 * @property bool $is_base
 * @property bool $is_active
 * @property-read UnitGroup $unitGroup
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

    /** @return BelongsTo<UnitGroup, $this> */
    public function unitGroup(): BelongsTo
    {
        return $this->belongsTo(UnitGroup::class);
    }
}
