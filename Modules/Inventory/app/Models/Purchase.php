<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Database\Factories\PurchaseFactory;
use Modules\Inventory\Enums\PurchaseStatus;
use Morilog\Jalali\Jalalian;

/**
 * @extends BaseModel<Purchase>
 *
 * @property string $slug
 * @property string $order_number
 * @property Warehouse $warehouse
 */
#[Fillable(['slug', 'supplier_id', 'warehouse_id', 'order_number', 'status', 'ordered_at', 'received_at', 'expected_at', 'notes'])]
class Purchase extends BaseModel
{
    /** @use HasFactory<PurchaseFactory> */
    use HasFactory;

    protected $casts = [
        'ordered_at' => 'datetime',
        'expected_at' => 'datetime',
        'received_at' => 'datetime',
        'status' => PurchaseStatus::class,
    ];

    protected static function newFactory(): PurchaseFactory
    {
        return PurchaseFactory::new();
    }

    /** @return BelongsTo<Warehouse, $this> */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /** @return BelongsTo<Supplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return HasMany<PurchaseItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function orderedAtJalali(): Attribute
    {

        return Attribute::make(
            get: function () {
                return $this->ordered_at ?
                    Jalalian::fromCarbon(Carbon::createFromImmutable($this->ordered_at)) : null;
            }
        );
    }

    public function receivedAtJalali(): Attribute
    {

        return Attribute::make(
            get: function () {
                return $this->received_at ?
                    Jalalian::fromCarbon(Carbon::createFromImmutable($this->received_at)) : null;
            }
        );
    }

    public function expectedAtJalali(): Attribute
    {

        return Attribute::make(
            get: function () {
                return $this->expected_at ?
                    Jalalian::fromCarbon(Carbon::createFromImmutable($this->expected_at)) : null;
            }
        );
    }
}
