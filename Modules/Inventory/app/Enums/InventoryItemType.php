<?php

namespace Modules\Inventory\app\Enums;

enum InventoryItemType: string
{
    case PART = 'part';
    case FLUID = 'fluid';
    case CONSUMABLE = 'consumable';
    case ACCESSORY = 'accessory';
}
