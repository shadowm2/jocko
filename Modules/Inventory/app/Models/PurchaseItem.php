<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Inventory\Database\Factories\PurchaseItemFactory;

/**
 * @property string $slug
 * @property int $purchase_id
 * @property int $item_id
 * @property float $quantity
 * @property float $received_quantity
 * @property float $unit_price
 * @property float $discount
 * @property float $tax
 * @property float $total
 */
#[Fillable(['slug', 'purchase_id', 'item_id', 'quantity', 'received_quantity', 'unit_price', 'discount', 'tax', 'total'])]
class PurchaseItem extends BaseModel
{
    use HasFactory;

    protected static function newFactory(): PurchaseItemFactory
    {
        return PurchaseItemFactory::new();
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * @return BelongsTo<Item, $this>
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
