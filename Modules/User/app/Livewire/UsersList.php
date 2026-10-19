<?php

namespace Modules\User\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableFilters;
use Modules\Inventory\Livewire\Concerns\InteractsWithTableSorting;
use Modules\User\Enums\UserType;
use Modules\User\Services\UserService;

class UsersList extends Component
{
    use InteractsWithTableFilters;
    use InteractsWithTableSorting;
    use WithPagination;

    /** @var array<string, mixed> */
    public array $filters = ['search' => '', 'type' => ''];

    public string $sortBy = 'last_name';

    public string $sortDirection = 'asc';

    protected function defaultTableFilters(): array
    {
        return ['search' => '', 'type' => ''];
    }

    /** @return array<int, array<string, mixed>> */
    private function filterDefinitions(): array
    {
        $types = collect(UserType::cases())->mapWithKeys(fn ($type) => [$type->value => ucfirst($type->value)])->all();

        return [
            ['name' => 'search', 'type' => 'search', 'label' => __('inventory::strings.Search'), 'placeholder' => __('user::strings.Search Users')],
            ['name' => 'type', 'type' => 'select', 'label' => __('user::attributes.User Type'), 'placeholder' => __('strings.All'), 'options' => $types],
        ];
    }

    /** @return array<string, string> */
    private function sortOptions(): array
    {
        return ['first_name' => __('user::attributes.First Name'), 'last_name' => __('user::attributes.Last Name'), 'email' => __('user::attributes.Email'), 'type' => __('user::attributes.User Type')];
    }

    public function onEditClick(): void
    {
        Flux::toast(
            text: 'Color created successfully.',
            variant: 'success',
        );
    }

    public function render(UserService $userService): View
    {

        $columns = [
            [
                'label' => __('user::attributes.First Name'),
                'key' => 'first_name',
            ],
            [
                'label' => __('user::attributes.Last Name'),
                'key' => 'last_name',
            ],
            [
                'label' => __('user::attributes.Email'),
                'key' => 'email',
            ],
            [
                'label' => '',
            ],
        ];
        $users = $userService->getUsers([...$this->filters, 'sort_by' => $this->sortBy, 'sort_direction' => $this->sortDirection]);

        return view('user::livewire.users-list', [
            ...compact('columns'),
            'rows' => $users,
            'filterDefinitions' => $this->filterDefinitions(),
            'sortOptions' => $this->sortOptions(),
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.Users'),
            ]);
    }
}
