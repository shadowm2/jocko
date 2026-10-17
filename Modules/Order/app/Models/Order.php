<?php

namespace Modules\Order\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Car\Models\UserCar;
use Modules\Inventory\Models\Warehouse;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Enums\OrderStatus;
use Modules\User\Models\User;

/**
 * @extends BaseModel<Order>
 *
 * @property int $user_id
 * @property int $user_car_id
 * @property int $warehouse_id
 * @property string $slug
 * @property string $order_number
 * @property OrderStatus $status
 * @property ?string $description
 */
#[Fillable(['slug', 'user_id', 'user_car_id', 'warehouse_id', 'order_number', 'status', 'description'])]
class Order extends BaseModel
{
    /**
     * @use HasFactory<OrderFactory>
     */
    use HasFactory;

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * ponytail: max id + 1 can collide under concurrent creates (unique
     * index turns that into an error); use a counter table if it ever bites.
     */
    public static function nextNumber(): string
    {
        return 'ORD-'.str_pad((string) ((int) static::withTrashed()->max('id') + 1), 5, '0', STR_PAD_LEFT);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserCar, $this>
     */
    public function userCar(): BelongsTo
    {
        return $this->belongsTo(UserCar::class);
    }

    /**
     * @return BelongsTo<Warehouse, $this>
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
