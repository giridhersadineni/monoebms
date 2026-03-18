<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hall_ticket'   => $this->faker->unique()->numerify('##########'),
            'dob'           => $this->faker->date('Y-m-d', '-18 years'),
            'dost_id'       => null,
            'name'          => $this->faker->name(),
            'father_name'   => $this->faker->name('male'),
            'mother_name'   => $this->faker->name('female'),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->phoneNumber(),
            'aadhaar'       => $this->faker->unique()->numerify('############'),
            'gender'        => $this->faker->randomElement(['M', 'F']),
            'caste'         => $this->faker->randomElement(['OC', 'BC-A', 'BC-B', 'SC', 'ST']),
            'course'        => 'BA',
            'course_name'   => 'Bachelor of Arts',
            'group_code'    => 'EC',
            'medium'        => 'EM',
            'semester'      => 3,
            'admission_year' => 2022,
            'scheme'        => 'CBCS',
            'is_active'     => true,
        ];
    }

    public function withDostId(): static
    {
        return $this->state(fn (array $attributes) => [
            'dost_id' => $this->faker->unique()->numerify('DOST##########'),
        ]);
    }

    public function feePaid(): static
    {
        return $this->state([]);
    }
}
