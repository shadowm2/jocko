<?php

namespace Modules\Dashboard\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Dashboard\Enums\CategoryType;
use Modules\Dashboard\Livewire\Forms\CategoryForm;
use Modules\Dashboard\Services\CategoryService;

class CategoryCreate extends Component
{
    public CategoryForm $form;

    public function mount(): void
    {
        $this->form->country = '';
    }

    public function save(): void
    {
        $this->form->save();
        Flux::toast(text: __('dashboard::messages.Category Created Successfully'), variant: 'success');
        $this->redirectRoute('categories.index', navigate: true);
    }

    public function render(): View
    {
        $categoryService = resolve(CategoryService::class);
        $categories = $categoryService->getCategories(paginate: false);
        $types = CategoryType::cases();

        return view('dashboard::livewire.categories-create', [
            ...compact('categories', 'types'),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('dashboard::strings.Category Create'),
            ]);
    }
}
