<?php

namespace Modules\Inventory\Services;

use App\Services\BaseService;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Repositories\StockMovementRepository;

/**
 * @extends BaseService<StockMovement, StockMovementRepository>
 */
class StockMovementService extends BaseService
{
    public function __construct(
        StockMovementRepository $repository
    ) {
        parent::__construct($repository);
    }

    //    public function purchase(): StockMovement
    //    {
    //        // increase warehouse stock
    //        // create movement
    //    }
    //
    //    public function consume(): StockMovement
    //    {
    //        // decrease warehouse stock
    //        // create movement
    //    }
    //
    //    public function transfer(): StockMovement
    //    {
    //        // decrease source
    //        // increase destination
    //        // create movement
    //    }
    //
    //    public function adjust(): StockMovement
    //    {
    //        // change stock
    //        // create movement
    //    }
    //
    //    public function return(): StockMovement {}
}
