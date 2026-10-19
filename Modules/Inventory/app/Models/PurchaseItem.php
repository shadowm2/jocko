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
 * @property-read Item $item
 *
 * @extends BaseModel<PurchaseItem>
 */
#[Fillable(['slug', 'purchase_id', 'item_id', 'unit_id', 'quantity', 'received_quantity', 'unit_price', 'discount', 'tax', 'total'])]
class PurchaseItem extends BaseModel
{
    /** @use HasFactory<PurchaseItemFactory> */
    use HasFactory;

    protected static function newFactory(): PurchaseItemFactory
    {
        return PurchaseItemFactory::new();
    }

    /** @return BelongsTo<Purchase, $this> */
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

    /** @return BelongsTo<Unit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
