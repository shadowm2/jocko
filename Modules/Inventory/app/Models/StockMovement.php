<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Inventory\Enums\StockMovementType;
use Modules\User\Database\Factories\StockMovementFactory;
use Modules\User\Models\User;

// use Modules\Inventory\Database\Factories\StockMovementFactory;

#[Fillable(['slug', 'item_id', 'warehouse_item_id', 'user_id', 'from_warehouse_id', 'to_warehouse_id', 'quantity', 'unit_cost', 'total_cost', 'balance_after', 'type', 'reference_type', 'reference_id', 'description'])]
class StockMovement extends BaseModel
{
    /** @use HasFactory<StockMovementFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $casts = [
        'type' => StockMovementType::class,
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'total_cost' => 'decimal:4',
        'balance_after' => 'decimal:4',
    ];

    protected static function newFactory(): StockMovementFactory
    {
        // return StockMovementFactory::new();
    }

    /**
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    /**
     * The purchase or order that caused the movement, if any.
     *
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * 1 when stock comes into a warehouse, -1 when it leaves one, 0 for
     * a transfer between two warehouses.
     */
    public function direction(): int
    {
        return match (true) {
            $this->to_warehouse_id && ! $this->from_warehouse_id => 1,
            $this->from_warehouse_id && ! $this->to_warehouse_id => -1,
            default => 0,
        };
    }
}
