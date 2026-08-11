<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @template TModel of Model
 *
 * @mixin Builder<TModel>
 */
abstract class BaseModel extends Model
{
    /*
     |--------------------------------------------------------------------------
     | Common traits
     |--------------------------------------------------------------------------
     */

    use SoftDeletes;

    /*
     |--------------------------------------------------------------------------
     | Default settings
     |--------------------------------------------------------------------------
     */

    protected $guarded = [];

    /*
     |--------------------------------------------------------------------------
     | Boot methods
     |--------------------------------------------------------------------------
     */

    protected static function booted(): void
    {
        static::creating(function (Model $model) {
            // Common creating logic
        });

        static::updating(function (Model $model) {
            // Common updating logic
        });
    }

    /*
     |--------------------------------------------------------------------------
     | Query helpers
     |--------------------------------------------------------------------------
     */

    /**
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest('id');
    }

    /**
     * @param  Builder<TModel>  $query
     * @param  array<string>  $columns
     * @return Builder<TModel>
     */
    public function scopeSearch(
        Builder $query,
        ?string $search,
        array $columns = []
    ): Builder {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search, $columns) {

            foreach ($columns as $column) {
                $query->orWhere(
                    $column,
                    'like',
                    "%{$search}%"
                );
            }

        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
