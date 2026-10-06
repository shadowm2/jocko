<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Enums\StockMovementType;
use Modules\User\Database\Factories\StockMovementFactory;

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
}
