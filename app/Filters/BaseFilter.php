<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class BaseFilter
{
    /** @var Builder<TModel> */
    protected Builder $builder;

    /**
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel> $builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->filter();

        return $this->builder;
    }

    abstract protected function filter(): void;

    protected function where(
        string $column,
        mixed $value
    ): void {
        if ($value !== null && $value !== '') {
            $this->builder->where($column, $value);
        }
    }
}
