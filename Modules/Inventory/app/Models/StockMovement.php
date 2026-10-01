<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Database\Factories\StockMovementFactory;

// use Modules\Inventory\Database\Factories\StockMovementFactory;

#[Fillable(['item_id', 'from_warehouse_id', 'to_warehouse_id', 'quantity', 'type', 'reference_type', 'reference_id', 'description'])]
class StockMovement extends BaseModel
{
    /** @use HasFactory<StockMovementFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): StockMovementFactory
    {
        // return StockMovementFactory::new();
    }
}
