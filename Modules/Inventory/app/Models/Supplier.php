<?php

namespace Modules\Inventory\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Dashboard\Models\City;
use Modules\Inventory\Database\Factories\SupplierFactory;
use Modules\User\Models\User;

// use Modules\Inventory\Database\Factories\SupplierFactory;
/**
 * @property int $id
 * @property int $user_id
 * @property string $notes
 * @property string $website
 * @property string $postal_code
 * @property string $address
 * @property string $tax_number
 * @property bool $is_active
 * @property string $phone
 * @property string $slug
 */
#[Fillable(['user_id', 'slug',  'notes', 'phone', 'city_id', 'address', 'postal_code', 'website', 'tax_number', 'is_active'])]
class Supplier extends BaseModel
{
    /**
     * @use HasFactory<SupplierFactory>
     */
    use HasFactory;

    protected static function newFactory(): SupplierFactory
    {
        return SupplierFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
