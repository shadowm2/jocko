<?php

namespace Modules\User\Database\Factories;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\UserType;
use Modules\User\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $slug = Utils::generateUniqueSlug($firstName.' '.$lastName, User::class);
        $password = mb_strtolower($firstName);
        $type = $this->faker->boolean(90)
         ? UserType::Customer : UserType::Admin;

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'type' => $type,
            'slug' => $slug,
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => Utils::iranianMobile(),
            'password' => Hash::make($password),
        ];
    }
}
