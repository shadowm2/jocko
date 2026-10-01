<?php

namespace Modules\Inventory\Enums;

enum StockMovementType: string
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
    case TRANSFER = 'transfer';
    case RETURN = 'return';
    case ADJUSTMENT = 'adjustment';
    case CONSUMPTION = 'consumption';
    case DAMAGE = 'damage';
    case INITIAL_STOCK = 'initial_stock';
}
