<?php

namespace Modules\Inventory\Repositories\Contracts;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Unit;

/**
 * @extends BaseRepositoryInterface<StockMovement>
 */
interface StockMovementRepositoryInterface extends BaseRepositoryInterface {}
