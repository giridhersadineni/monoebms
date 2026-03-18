<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamEnrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_id', 'student_id', 'hall_ticket', 'exam_type', 'fee_amount',
        'fee_paid_at', 'challan_number', 'challan_submitted_on',
        'challan_received_by', 'enrolled_at',
    ];

    protected $casts = [
        'fee_paid_at'          => 'datetime',
        'challan_submitted_on' => 'date',
        'enrolled_at'          => 'datetime',
        'fee_amount'           => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function enrollmentSubjects(): HasMany
    {
        return $this->hasMany(ExamEnrollmentSubject::class, 'enrollment_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'enrollment_id');
    }

    public function gpa(): HasOne
    {
        return $this->hasOne(Gpa::class, 'enrollment_id');
    }

    public function revaluation(): HasOne
    {
        return $this->hasOne(RevaluationEnrollment::class, 'original_enrollment_id');
    }

    public function isFeePaid(): bool
    {
        return $this->fee_paid_at !== null;
    }

    public function getFeeStatus(): string
    {
        if ($this->fee_paid_at !== null) {
            return 'paid';
        }
        if ($this->challan_submitted_on !== null) {
            return 'challan_submitted';
        }

        return 'pending';
    }

    public function scopeFeePaid($query)
    {
        return $query->whereNotNull('fee_paid_at');
    }

    public function scopeFeeUnpaid($query)
    {
        return $query->whereNull('fee_paid_at');
    }

    public function scopeForExam($query, int $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
