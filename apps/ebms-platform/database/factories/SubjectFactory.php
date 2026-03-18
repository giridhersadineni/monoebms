<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'       => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'name'       => $this->faker->words(3, true),
            'course'     => 'BA',
            'group_code' => 'EC',
            'medium'     => 'EM',
            'semester'   => 3,
            'paper_type' => 'compulsory',
            'part'       => 1,
            'scheme'     => 'CBCS',
        ];
    }
}
