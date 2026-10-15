<?php

namespace Modules\User\Filters;

use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Enums\UserType;

class UserFilter extends BaseFilter
{
    protected function filter(): void
    {
        $search = trim((string) ($this->filters['search'] ?? ''));
        if ($search !== '') {
            $this->builder->where(function (Builder $query) use ($search): void {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $type = $this->filters['type'] ?? '';
        if (in_array($type, array_column(UserType::cases(), 'value'), true)) {
            $this->builder->where('type', $type);
        }

        $sortBy = $this->filters['sort_by'] ?? null;
        if (in_array($sortBy, ['first_name', 'last_name', 'email', 'type'], true)) {
            $this->builder
                ->orderBy($sortBy, ($this->filters['sort_direction'] ?? '') === 'desc' ? 'desc' : 'asc')
                ->orderBy('users.id');
        }
    }
}
