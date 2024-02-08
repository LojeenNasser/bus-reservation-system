<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $gender = Arr::random(['Male', 'Female']);

        return [
            'first_name' => $this->faker->{"firstName$gender"},
            'last_name' => $this->faker->{"lastName$gender"},
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$YRacvPG4ZGxRt88Pln1eWuUEMJNFpFtmxneaTld.jnJDNYX1c7Hsa', // BbsPass123
        ];
    }
}
