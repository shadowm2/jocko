<?php

namespace Modules\Auth\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Modules\User\Models\User;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string,mixed>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'first_name' => $this->nameRules(),
            'last_name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'mobile' => $this->mobileRules($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, ValidationRule|array<int,mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, ValidationRule|array<int,mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'nullable',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }

    /**
     * @return array<int,mixed>
     */
    protected function mobileRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'regex:/^09[0-9]{9}$/',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }
}
