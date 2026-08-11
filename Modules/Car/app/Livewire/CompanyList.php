<?php

namespace Modules\Car\Livewire;

use App\Livewire\BaseComponent;
use Illuminate\Contracts\View\View;
use Modules\Car\Services\CarCompanyService;

class CompanyList extends BaseComponent
{
    public function render(CarCompanyService $companyService): View
    {

        $columns = [
            [
                'label' => __('car::strings.Car Company Name'),
                'key' => 'name',
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
