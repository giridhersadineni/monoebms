<?php

namespace App\Services;

use App\Models\Exam;

class FeeCalculatorService
{
    public function calculate(Exam $exam, int $subjectCount, ?string $course = null, ?string $groupCode = null): int
    {
        return $exam->calculateFee($subjectCount, $course, $groupCode);
    }
}
