<?php

namespace Modules\Order\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function getDefault(): self
    {
        return self::PENDING;
    }

    /**
     * Translated display label.
     */
    public function label(): string
    {
        return (string) __("order::strings.order_statuses.{$this->value}");
    }

    /**
     * Badge colour, so the state is readable at a glance.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'zinc',
            self::IN_PROGRESS => 'amber',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }
}
