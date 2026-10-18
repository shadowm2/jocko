<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Database\Factories\WarehouseItemFactory;

// use Modules\Inventory\Database\Factories\WarehouseItemFactory;

/**
 * @property string $slug
 * @property ?float $min_quantity
 * @property ?float $max_quantity
 * @property float $quantity
 */
#[Fillable(['slug', 'item_id', 'warehouse_id', 'quantity', 'min_quantity', 'max_quantity'])]
class WarehouseItem extends BaseModel
{
    use HasFactory;

    protected static function newFactory(): WarehouseItemFactory
    {
        return WarehouseItemFactory::new();
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return BelongsTo<Item>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * At or below its minimum; items without a minimum never run low.
     *
     * @param  Builder<WarehouseItem>  $query
     */
    #[Scope]
    protected function lowStock(Builder $query): void
    {
        $query->whereNotNull('min_quantity')->whereColumn('quantity', '<=', 'min_quantity');
    }

    public function isLowStock(): bool
    {
        return $this->min_quantity !== null && (float) $this->quantity <= (float) $this->min_quantity;
    }
}
