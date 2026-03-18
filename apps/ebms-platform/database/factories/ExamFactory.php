<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'             => 'B.A. Semester 3 Examination Nov ' . $this->faker->year(),
            'semester'         => 3,
            'course'           => 'BA',
            'exam_type'        => 'regular',
            'month'            => 'November',
            'year'             => 2024,
            'status'           => 'open',
            'scheme'           => 'CBCS',
            'revaluation_open' => false,
            'fee_json'         => ['BA' => ['regular' => 500, 'above_2_sem' => 700], 'BCOM' => ['regular' => 600, 'above_2_sem' => 800]],
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => 'open']);
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed']);
    }

    public function withRevaluationOpen(): static
    {
        return $this->state(['revaluation_open' => true, 'status' => 'closed']);
    }
}
