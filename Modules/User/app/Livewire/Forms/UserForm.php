<?php

namespace Modules\User\Livewire\Forms;

use Livewire\Form;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;
use Modules\User\Services\UserService;

class UserForm extends Form
{
    public ?User $user = null;

    public string $first_name;

    public string $last_name;

    public string $email;

    public ?string $mobile;

    public ?string $password;

    public ?string $password_confirmation;

    /**
     * @return array<string, mixed>
     */
    public function getRules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:Modules\User\Models\User,email'.($this->user ? (','.$this->user->id) : '')],
            'mobile' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed'],
        ];
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->mobile = $user->mobile;
    }

    public function save(): User
    {
        $userService = resolve(UserService::class);
        $data = $this->validate();

        if (isset($this->user)) {
            // Update
            $userService->update($this->user, $data);

            return $this->user;
        } else {
            // Create
            $data = [
                ...$data,
                'type' => UserType::Customer->value,
            ];

            return $userService->create($data);
        }
    }
}
