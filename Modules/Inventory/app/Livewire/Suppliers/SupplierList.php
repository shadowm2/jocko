<?php

namespace Modules\Inventory\Livewire\Suppliers;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Models\Supplier;
use Modules\Inventory\Services\SupplierService;

class SupplierList extends Component
{
    use WithPagination;

    public function toggleIsActive(string $supplierSlug): void
    {
        $supplierService = resolve(SupplierService::class);

        /** @var Supplier $supplier */
        $supplier = $supplierService->findByKey($supplierSlug);

        $supplierService->update($supplier, [
            'is_active' => ! $supplier->is_active,
        ]);

        Flux::toast(__('inventory::messages.Supplier Updated Successfully'), variant: 'success');
    }

    public function render(): View
    {
        $columns = [
            [
                'label' => __('inventory::attributes.Supplier Name'),
                'key' => 'user.first_name,user.last_name',
            ],
            [
                'label' => __('dashboard::attributes.City'),
                'key' => 'city.name',
            ],
            [
                'label' => __('inventory::attributes.Supplier Phone'),
                'key' => 'phone',
            ],
            [
                'label' => __('inventory::attributes.Supplier Address'),
                'key' => 'address',
                'maxlength' => 45,
            ],
            [
                'label' => __('inventory::attributes.Supplier Website'),
                'key' => 'website',
            ],
            [
                'label' => __('inventory::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => __('strings.Actions'),
                'component' => 'inventory::supplier-action-cell',
            ],
        ];
        $supplierService = resolve(SupplierService::class);
        $rows = $supplierService->getSuppliers();

        return view('inventory::livewire.suppliers-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('inventory::strings.Suppliers'),
            ]);
    }
}
