<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Dashboard\Models\Province;
use Modules\Dashboard\Services\ProvinceService;

class ProvinceList extends Component
{
    use WithPagination;

    public function toggleIsActive($provinceSlug): void
    {
        $provinceService = resolve(ProvinceService::class);
        /** @var Province $province */
        $province = $provinceService->findByKey($provinceSlug);
        $provinceService->update($province, [
            'is_active' => ! $province->is_active,
        ]);
        Flux::toast(__('dashboard::messages.Province Updated Successfully'), variant: 'success');
    }

    public function render(ProvinceService $provinceService): View
    {
        $columns = [
            [
                'label' => __('dashboard::attributes.Province Name'),
                'key' => 'name',
            ],
            [
                'label' => __('dashboard::attributes.Province Country'),
                'key' => 'country.name',
            ],
            [
                'label' => __('dashboard::attributes.Is Active'),
                'key' => 'is_active',
                'type' => 'switch',
                'action' => 'toggleIsActive',
            ],
            [
                'label' => '',
                'component' => 'dashboard::province-action-cell',
            ],
        ];
        $rows = $provinceService->getProvinces();

        return view('dashboard::livewire.provinces-list', [
            ...compact('columns', 'rows'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Provinces'),
            ]);
    }
}
