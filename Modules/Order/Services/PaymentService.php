<?php

namespace Modules\Order\Services;

use App\Helpers\Utils;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Order\Models\Order;
use Modules\Order\Models\Payment;
use Modules\Order\Repositories\PaymentRepository;

/**
 * @extends BaseService<Payment, PaymentRepository>
 */
class PaymentService extends BaseService
{
    public function __construct(
        PaymentRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return LengthAwarePaginator<int, Payment>|Collection<int, Payment>
     */
    public function getPayments(array $filters = [], bool $paginate = true): LengthAwarePaginator|Collection
    {
        $this->repository
            ->filtered($filters)
            ->withRelations();

        return $paginate ? $this->repository->paginate() : $this->repository->all();
    }

    public function create(array $data): Payment
    {
        /** @var Order $order */
        $order = resolve(OrderService::class)->findByKey($data['order']);
        $data['order_id'] = $order->id;
        $data['slug'] ??= Utils::generateUniqueSlug('pay '.$order->order_number, Payment::class);
        unset($data['order']);

        /** @var Payment $payment */
        $payment = parent::create($data);

        return $payment;
    }
}
