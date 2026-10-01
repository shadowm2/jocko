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
     * @param  array<string,mixed>  $filters
     */
    public function __construct(
        protected readonly array $filters
    ) {}

    /**
     * @param  Builder<TModel>  $builder
     * @return Builder<TModel> $builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        $this->filter();
        $this->checkFilters();

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

    protected function checkFilters(): void
    {
        if (isset($this->filters['exclude'])) {
            $excludeList = $this->filters['exclude'][0];
            $col = $this->filters['exclude'][1] ?? 'id';
            $excludeList = is_iterable($excludeList) ? $excludeList : [$excludeList];
            $this->builder->whereNotIn($col, $excludeList);
        }
        if (isset($this->filters['where'])) {
            $whereFilters = $this->filters['where'];
            $this->builder->where($whereFilters);
        }
        if (isset($this->filters['whereIn'])) {
            $whereInFilters = $this->filters['whereIn'];
            $this->builder->whereIn(...$whereInFilters);
        }
        if (isset($this->filters['whereNotIn'])) {
            $whereNotInFilters = $this->filters['whereNotIn'];
            $this->builder->whereNotIn(...$whereNotInFilters);
        }
    }

    protected function hasFilter(string $field): bool
    {
        return in_array($field, array_keys($this->filters));
    }
}
