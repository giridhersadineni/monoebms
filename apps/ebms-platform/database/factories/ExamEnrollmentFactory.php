<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamEnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_id'    => Exam::factory(),
            'student_id' => Student::factory(),
            'hall_ticket' => $this->faker->numerify('##########'),
            'exam_type'  => 'regular',
            'fee_amount' => 500,
            'fee_paid_at' => null,
            'enrolled_at' => now(),
        ];
    }

    public function feePaid(): static
    {
        return $this->state([
            'fee_paid_at'          => now(),
            'challan_number'       => $this->faker->numerify('CHLN#####'),
            'challan_submitted_on' => today(),
            'challan_received_by'  => 'Admin',
        ]);
    }

    public function feePending(): static
    {
        return $this->state(['fee_paid_at' => null]);
    }
}
