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
            'status'           => 'RUNNING',
            'scheme'           => 'CBCS',
            'revaluation_open' => false,
            'fee_regular'      => 650,
            'fee_supply_upto2' => null,
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => 'RUNNING']);
    }

    public function closed(): static
    {
        return $this->state(['status' => 'CLOSED']);
    }

    public function supplementary(): static
    {
        return $this->state(['exam_type' => 'supplementary', 'fee_supply_upto2' => 550]);
    }

    public function improvement(): static
    {
        return $this->state(['exam_type' => 'improvement', 'fee_improvement' => 300]);
    }

    public function withRevaluationOpen(): static
    {
        return $this->state(['revaluation_open' => true, 'status' => 'REVALOPEN']);
    }
}
