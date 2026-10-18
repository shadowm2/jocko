<?php

namespace Modules\Order\Enums;

/**
 * Derived from what an order has been paid so far; never stored.
 */
enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PARTIAL = 'partial';
    case PAID = 'paid';

    public static function for(float $total, float $paid): self
    {
        return match (true) {
            round($paid, 2) <= 0 => self::UNPAID,
            round($paid, 2) >= round($total, 2) => self::PAID,
            default => self::PARTIAL,
        };
    }

    /**
     * Translated display label.
     */
    public function label(): string
    {
        return (string) __("order::strings.payment_statuses.{$this->value}");
    }

    /**
     * Badge colour, so the state is readable at a glance.
     */
    public function color(): string
    {
        return match ($this) {
            self::UNPAID => 'red',
            self::PARTIAL => 'amber',
            self::PAID => 'green',
        };
    }
}
