<?php

namespace Modules\Auth\Actions\Fortify;

use App\Helpers\Utils;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Modules\Auth\Concerns\PasswordValidationRules;
use Modules\Auth\Concerns\ProfileValidationRules;
use Modules\User\Models\User;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $slug = Utils::generateUniqueSlug($input['first_name'].' '.$input['last_name'], User::class);

        return User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'mobile' => $input['mobile'],
            'password' => $input['password'],
            'slug' => $slug,
            'type' => $input['type'],
        ]);
    }
}
