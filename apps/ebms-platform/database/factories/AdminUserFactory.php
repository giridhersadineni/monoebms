<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'login_id'  => $this->faker->unique()->userName(),
            'name'      => $this->faker->name(),
            'password'  => Hash::make('password'),
            'role'      => 'staff',
            'is_active' => true,
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function superadmin(): static
    {
        return $this->state(['role' => 'superadmin']);
    }
}
