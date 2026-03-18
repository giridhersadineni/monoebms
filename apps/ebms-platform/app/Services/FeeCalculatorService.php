<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Student;

class FeeCalculatorService
{
    public function calculate(Exam $exam, Student $student): int
    {
        // Determine if student is more than 2 semesters behind
        $above2 = $student->semester !== null && $exam->semester < ($student->semester - 2);

        return $exam->getFeeForCourse($student->course ?? '', $above2);
    }
}
