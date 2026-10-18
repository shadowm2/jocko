<?php

namespace Modules\Order\Repositories;

use App\Repositories\BaseRepository;
use Modules\Order\Filters\PaymentFilter;
use Modules\Order\Models\Payment;

/**
 * @extends BaseRepository<Payment>
 */
class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function withRelations(): PaymentRepository
    {
        $this->query()->with(['order.user']);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function filtered(array $filters): PaymentRepository
    {
        (new PaymentFilter($filters))
            ->apply($this->query());

        return $this;
    }
}
