<?php

namespace Modules\Car\Repositories\Contracts;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Modules\Car\Models\Car;
use Modules\Car\Repositories\CarRepository;

/**
 * @extends BaseRepositoryInterface<Car>
 */
interface CarRepositoryInterface extends BaseRepositoryInterface
{
    public function filtered(array $filters): CarRepository;
}
