<?php

namespace App\Services;

use App\Models\ExamEnrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ChallanPdfService
{
    public const SBI_ACCOUNT = '52010041880';
    public const SBI_BRANCH  = 'Subedari Branch';

    public function generate(ExamEnrollment $enrollment): Response
    {
        $data = [
            'enrollment'  => $enrollment,
            'student'     => $enrollment->student,
            'exam'        => $enrollment->exam,
            'subjects'    => $enrollment->enrollmentSubjects,
            'challan_no'  => $enrollment->id,
            'sbi_account' => self::SBI_ACCOUNT,
            'sbi_branch'  => self::SBI_BRANCH,
            'copies'      => ['Quadruplicate', 'Triplicate', 'Duplicate', 'Original'],
        ];

        $pdf = Pdf::loadView('student.challan.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download("challan-{$enrollment->id}.pdf");
    }
}
