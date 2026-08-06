<?php

namespace Modules\Car\Models;

use App\Helpers\Utils;
use App\Models\BaseModel;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Modules\User\Database\Factories\UserCarFactory;
use Modules\User\Models\User;
use Morilog\Jalali\Jalalian;

/**
 * @extends BaseModel<UserCar>
 *
 * @property CarbonImmutable|null $manufactured_at
 */
#[Fillable('description', 'manufactured_at', 'car_id')]
class UserCar extends BaseModel
{
    /**
     * @use HasFactory<UserCarFactory>
     */
    use AsPivot, HasFactory;

    protected $casts = [
        'manufactured_at' => 'datetime',
    ];

    protected static function newFactory(): UserCarFactory
    {
        return UserCarFactory::new();
    }

    public function getManufacturedAtJalaliAttribute(): ?string
    {
        return $this->manufactured_at
            ? Utils::pDigits(Jalalian::fromCarbon($this->manufactured_at->toMutable())->format('Y/m/d'))
            : null;
    }

    public function setManufacturedAtAttribute(?string $value): void
    {
        $value = Utils::eDigits($value);

        if (blank($value)) {
            $this->attributes['manufactured_at'] = null;

            return;
        }

        $this->attributes['manufactured_at'] =
            Jalalian::fromFormat('Y/m/d', $value)
                ->toCarbon()
                ->toDateString();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Car, $this>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * @return BelongsTo<Color, $this>
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
