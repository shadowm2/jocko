<?php

namespace Modules\Order\app\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Car\Models\UserCar;
use Modules\Order\database\factories\OrderFactory;
use Modules\User\Models\User;

/**
 * @extends BaseModel<Order>
 *
 * @property int $user_id
 * @property int $user_car_id
 * @property string $description
 */
#[Fillable(['slug', 'user_id', 'user_car_id', 'description'])]
class Order extends BaseModel
{
    /**
     * @use HasFactory<OrderFactory>
     */
    use HasFactory;

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
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
}
