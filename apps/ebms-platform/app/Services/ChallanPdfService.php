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
        $enrollment->load(['student', 'exam', 'enrollmentSubjects.subject']);

        $data = [
            'enrollment'   => $enrollment,
            'student'      => $enrollment->student,
            'exam'         => $enrollment->exam,
            'subjects'     => $enrollment->enrollmentSubjects,
            'challan_no'   => $enrollment->id,
            'sbi_account'  => self::SBI_ACCOUNT,
            'sbi_branch'   => self::SBI_BRANCH,
            'fee_in_words' => $this->amountInWords($enrollment->fee_amount ?? 0),
        ];

        $pdf = Pdf::loadView('student.challan.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false)
            ->setOption('defaultMediaType', 'print');

        return $pdf->download("challan-{$enrollment->id}.pdf");
    }

    private function amountInWords(int $amount): string
    {
        if ($amount === 0) {
            return 'Zero Only';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen',
        ];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        $words = '';

        if ($amount >= 1000) {
            $words .= $ones[(int) ($amount / 1000)] . ' Thousand ';
            $amount %= 1000;
        }
        if ($amount >= 100) {
            $words .= $ones[(int) ($amount / 100)] . ' Hundred ';
            $amount %= 100;
        }
        if ($amount >= 20) {
            $words .= $tens[(int) ($amount / 10)] . ' ';
            $amount %= 10;
        }
        if ($amount > 0) {
            $words .= $ones[$amount] . ' ';
        }

        return trim($words) . ' Only';
    }
}
