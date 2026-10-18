<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Order\Enums\PaymentMethod;
use Modules\Order\Services\PaymentService;

class PaymentsList extends Component
{
    use InteractsWithTableFilters;
    use WithPagination;

    private const FILTERS = [
        'search' => '',
        'method' => '',
        'paid_from' => '',
        'paid_to' => '',
    ];

    /** @var array{search:string,method:string,paid_from:string,paid_to:string} */
    public array $filters = self::FILTERS;

    protected function defaultTableFilters(): array
    {
        return self::FILTERS;
    }

    public function render(PaymentService $paymentService): View
    {
        $columns = [
            ['label' => __('order::attributes.Order Number')],
            ['label' => __('order::attributes.User Name')],
            ['label' => __('order::attributes.Amount')],
            ['label' => __('order::attributes.Payment Method')],
            ['label' => __('order::attributes.Paid At')],
            [],
        ];

        $filterDefinitions = [
            ['name' => 'search', 'type' => 'search', 'label' => __('order::strings.Search'), 'placeholder' => __('order::strings.Search Orders')],
            ['name' => 'method', 'type' => 'select', 'label' => __('order::attributes.Payment Method'), 'placeholder' => __('strings.All'), 'options' => collect(PaymentMethod::cases())
                ->mapWithKeys(fn (PaymentMethod $method) => [$method->value => $method->label()])->all()],
            ['name' => 'paid_from', 'type' => 'date', 'label' => __('order::strings.Paid From'), 'placeholder' => __('strings.Select Date')],
            ['name' => 'paid_to', 'type' => 'date', 'label' => __('order::strings.Paid To'), 'placeholder' => __('strings.Select Date')],
        ];

        return view('order::livewire.payments-list', [
            'rows' => $paymentService->getPayments($this->filters),
            'columns' => $columns,
            'filterDefinitions' => $filterDefinitions,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('order::strings.Payments'),
            ]);
    }
}
