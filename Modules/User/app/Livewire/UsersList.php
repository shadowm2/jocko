<?php

namespace Modules\User\Livewire;

use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\User\Services\UserService;

class UsersList extends Component
{
    use WithPagination;

    public function onEditClick()
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
        $users = $userService->getUsers();

        return view('user::livewire.users-list', [
            ...compact('columns'),
            'rows' => $users,
        ])
            ->layout('dashboard::layouts.app')
            ->layoutData([
                'title' => __('user::strings.Users'),
            ]);
    }
}
