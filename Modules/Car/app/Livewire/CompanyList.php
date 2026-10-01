<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;
use Modules\Car\Services\CarCompanyService;

class CompanyList extends BaseComponent
{
    use WithPagination;

    public function render(CarCompanyService $companyService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Company Name'),
                'key' => 'name',
            ],
            [
                'label' => __('car::strings.Car Company Name'),
                'key' => 'slug',
            ],
            [
                'label' => __('car::strings.Car Company Name'),
                'attrs' => function ($row) {
                    return [
                        'image' => $row->logo,
                        'alt' => $row->name,
                        'initial' => mb_substr($row->name, 0, 1),
                        'name' => $row->name,
                    ];
                },
                'component' => 'dashboard::popup-image',
            ],
            [
                'label' => '',
                'component' => 'car::company-action-cell',
            ],
        ];
        $companies = $companyService->getCompanies();

        return view('car::livewire.companies-list', [
            ...compact('columns'),
            'rows' => $companies,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Cars'),
            ]);
    }
}
