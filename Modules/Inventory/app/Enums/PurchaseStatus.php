<?php

namespace Modules\Inventory\Enums;

enum PurchaseStatus: string
{
    /**
     * Display-only: shown for a purchase that has not been saved yet.
     * Never written to the purchases.status column.
     */
    case NEW = 'new';
    case DRAFT = 'draft';
    case ORDERED = 'ordered';
    case PARTIALLY_RECEIVED = 'partially_received';
    case RECEIVED = 'received';
    case CANCELLED = 'cancelled';

    public static function getDefault(): self
    {
        return self::DRAFT;
    }

    /**
     * Statuses that exist only in the interface and must never be stored.
     */
    public function isDisplayOnly(): bool
    {
        return $this === self::NEW;
    }

    /**
     * Case for a value read from the database. Display-only states cannot
     * come from storage, so they fall back to the default.
     */
    public static function tryFromStored(string $value): self
    {
        $status = self::tryFrom($value);

        if ($status === null || $status->isDisplayOnly()) {
            return self::getDefault();
        }

        return $status;
    }

    /**
     * Translated display label.
     */
    public function label(): string
    {
        return (string) __("inventory::strings.purchase_types.{$this->value}");
    }

    /**
     * Badge colour, so the state is readable at a glance.
     */
    public function color(): string
    {
        return match ($this) {
            self::NEW => 'blue',
            self::DRAFT => 'zinc',
            self::ORDERED => 'violet',
            self::PARTIALLY_RECEIVED => 'amber',
            self::RECEIVED => 'green',
            self::CANCELLED => 'red',
        };
    }
}
