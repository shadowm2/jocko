<?php

namespace Modules\Order\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Database\Factories\PaymentFactory;
use Modules\Order\Enums\PaymentMethod;
use Morilog\Jalali\Jalalian;

/**
 * @extends BaseModel<Payment>
 *
 * @property string $slug
 * @property int $order_id
 * @property float $amount
 * @property PaymentMethod $method
 * @property \Illuminate\Support\Carbon $paid_at
 * @property ?string $description
 * @property-read ?Jalalian $paid_at_jalali
 */
#[Fillable(['slug', 'order_id', 'amount', 'method', 'paid_at', 'description'])]
class Payment extends BaseModel
{
    /**
     * @use HasFactory<PaymentFactory>
     */
    use HasFactory;

    protected $casts = [
        'method' => PaymentMethod::class,
        'paid_at' => 'date',
    ];

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paidAtJalali(): Attribute
    {
        return Attribute::get(fn () => $this->paid_at ? Jalalian::fromDateTime($this->paid_at) : null);
    }
}
