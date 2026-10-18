<?php

namespace Modules\Inventory\Enums;

enum StockMovementType: string
{
    case PURCHASE = 'purchase';
    case RECEIPT = 'receipt';
    case SALE = 'sale';
    case TRANSFER = 'transfer';
    case RETURN = 'return';
    case ADJUSTMENT = 'adjustment';
    case CONSUMPTION = 'consumption';
    case DAMAGE = 'damage';
    case INITIAL_STOCK = 'initial_stock';

    /**
     * Translated display label.
     */
    public function label(): string
    {
        return (string) __("inventory::strings.stock_movement_types.{$this->value}");
    }
}
