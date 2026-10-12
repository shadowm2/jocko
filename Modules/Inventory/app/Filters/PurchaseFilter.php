<?php

namespace Modules\Inventory\Filters;

use App\Filters\BaseFilter;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Enums\PurchaseStatus;
use Morilog\Jalali\Jalalian;

class PurchaseFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier.user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $statuses = array_values(array_filter(
            (array) ($this->filters['statuses'] ?? []),
            fn (mixed $status): bool => is_string($status)
                && PurchaseStatus::tryFrom($status) !== null
                && PurchaseStatus::tryFrom($status)?->isDisplayOnly() === false,
        ));
        if ($statuses !== []) {
            $this->builder->whereIn('status', $statuses);
        }

        $supplierId = $this->filters['supplier_id'] ?? '';
        if (is_numeric($supplierId) && (int) $supplierId > 0) {
            $this->builder->where('supplier_id', (int) $supplierId);
        }

        $orderedFrom = $this->validDate($this->filters['ordered_from'] ?? null);
        if ($orderedFrom !== null) {
            $this->builder->whereDate('ordered_at', '>=', $orderedFrom);
        }

        $orderedTo = $this->validDate($this->filters['ordered_to'] ?? null);
        if ($orderedTo !== null) {
            $this->builder->whereDate('ordered_at', '<=', $orderedTo);
        }

        $sortColumns = [
            'ordered_at' => 'ordered_at',
            'order_number' => 'order_number',
            'status' => 'status',
            'received_at' => 'received_at',
        ];
        $sortBy = $sortColumns[$this->filters['sort_by'] ?? ''] ?? 'ordered_at';
        $direction = ($this->filters['sort_direction'] ?? '') === 'asc' ? 'asc' : 'desc';
        $this->builder->orderBy($sortBy, $direction)->orderBy('id', 'desc');
    }

    private function validDate(mixed $value): ?string
    {
        $value = Utils::eDigits($value);
        try {

            if (! is_string($value) || ! Jalalian::fromFormat('Y/m/d', $value)) {
                return null;
            }
        } catch (\Exception $e) {
            if ($value) {
                dd($value);
            }

            //            dd($e);
            return null;
        }

        return Jalalian::fromFormat('Y/m/d', $value)?->toCarbon()->toDateString();
    }
}
