<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['admin', 'tee', 'jrv']),
            'mesa_id' => null,
            'must_change_password' => false,
            'remember_token' => Str::random(10),
        ];
    }
}
